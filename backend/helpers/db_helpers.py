#!/usr/bin/env python
# encoding: utf-8

# Script supposed to be run in the background to populate the DB with available datasets 




## Setup

from xlrd import open_workbook
import csv
from csv import reader
import sys
from numbers import Number
import pymongo 
import logging 
import hashlib
import collections
from math import log
import pymongo
from pymongo import MongoClient
from bson.objectid import ObjectId
from prettytable import PrettyTable

import pprint
pp = pprint.PrettyPrinter(indent=4)


client = MongoClient()
db = client.cobra_db


species_col = db.species
mappings_col = db.mappings



import logging
logger = logging.getLogger('COBRA_HELPERS')
logger.setLevel(logging.DEBUG)
ch = logging.StreamHandler()
ch.setLevel(logging.DEBUG)
formatter = logging.Formatter('%(asctime)s - %(filename)s - %(message)s',"%Y-%m-%d %H:%M:%S")
ch.setFormatter(formatter)
logger.addHandler(ch)
# logger = logging.getLogger('COBRA_HELPERS')



def find_species_doc(txt):
	"""Free text search for the species docs"""
	return species_col.find_one({"$or":[{"full_name":txt},{"aliases":txt},{"abbrev_name":txt}]})

def aliases_for_species_matching(classification_query):
	possible_aliases_docs=species_col.aggregate([
		{"$match":classification_query},
		{"$unwind":"$aliases"},
		{"$project":{"aliases":1,"full_name":1,"name":1,"abbrev_name":1, "_id":0}}
	])['result']

	possible_names=set()
	for d in possible_aliases_docs:
		possible_names.add(d.get('abbrev_name',""))
		possible_names.add(d.get('aliases',""))
		possible_names.add(d.get('full_name',""))
	return list(possible_names)


def get_possible_aliases(species_txt):
	return aliases_for_species_matching({"_id":find_species_doc(species_txt)['_id']})

# def get_mapping_table(src,tgt):
# 	mapping_doc=mappings_col.find_one({"src":src,"tgt":tgt})
def parse_csv_table(src_file,column_keys,n_rows_to_skip,species_initials,id_col=None):
	rows_to_data=[]
	for initial in species_initials:
		logger.info("species initial:%s",initial)
	
	with open(src_file, 'rb') as f:
		csvreader = reader(f, delimiter='\t', quoting=csv.QUOTE_NONE)
		try:
			#logger.info("number of rows:%s",len(list(csvreader)))
			for row in csvreader:
				#logger.info("rows:%s",row[0][0]+row[0][1])
				for initial in species_initials:
					if (initial==row[0][0]+row[0][1]) and (row[0][2]!="R"):
						values=[]
						for i in range(0,2):
							values.append(row[i])
						#for col in row:
						#	values.append(col)
						
						if len(column_keys)!=len(values):
							logger.info("columns keys length:%d",len(column_keys))
							logger.info("value length :%d",len(values))
							logger.critical("Mismatching number of columns and number of keys at location\n%s/nrow:%d"%(src_file,len(row)))
						this_dict=dict(zip(column_keys,values))
						if id_col: #enforce id col type
							if isinstance(this_dict[id_col],Number):
								this_dict[id_col]=str(int(this_dict[id_col]))
						rows_to_data.append(this_dict)
						
         #logger.info("Successfully parsed %d rows of %d values",len(rows_to_data),len(column_keys)-1)
				
			#if id_col: #enforce id col type 
			#	if isinstance(this_dict[id_col],Number):
			#		this_dict[id_col]=str(int(this_dict[id_col]))
         #rows_to_data.append(this_dict)		
		except csv.Error as e:
			sys.exit('file %s, line %d: %s' % (filename, reader.line_num, e))

	return rows_to_data	
	#logger.info("currentsheet nrows:%d",(current_sheet.nrows))
	
	#logger.info("rows:%s",row)
	
	 

def parse_excel_table(src_file,column_keys,n_rows_to_skip,sheet_index,id_col=None):

        wb = open_workbook(src_file)
        current_sheet=wb.sheets()[sheet_index]
        current_sheet.name
        rows_to_data=[]
        logger.info("currentsheet nrows:%d",(current_sheet.nrows))
        for row in range(n_rows_to_skip,current_sheet.nrows):
                values=[row]
                for col in range(current_sheet.ncols):
                        values.append(current_sheet.cell(row,col).value)
                        #logger.info("currentsheet nrows:%s",(current_sheet.cell(row,col).value))
                if len(column_keys)!=len(values):
                			#logger.info("column keys:%d",(len(column_keys)))
                			#logger.info("lenght:%d",(len(values)))
                			logger.critical("Mismatching number of columns and number of keys at location\n%s/sheet:%d/nrow:%d"%(src_file,sheet_index,row))
                this_dict=dict(zip(column_keys,values))
                if id_col: #enforce id col type 
                        if isinstance(this_dict[id_col],Number):
                                this_dict[id_col]=str(int(this_dict[id_col]))

                rows_to_data.append(this_dict)


        logger.info("Successfully parsed %d rows of %d values",len(rows_to_data),len(column_keys)-1)
        return rows_to_data



def parse_ortholog_excel_table(src_file,column_keys,n_rows_to_skip,sheet_index,id_col=None):

	wb = open_workbook(src_file)
	current_sheet=wb.sheets()[sheet_index]
	current_sheet.name
	rows_to_data=[]
	clusters=[]
	logger.info("currentsheet nrows:%d",(current_sheet.nrows))
	for row in range(n_rows_to_skip,current_sheet.nrows):
		logger.info("row : %d",row)
		#logger.info("Successfully parsed %d rows of %d values",len(rows_to_data),len(column_keys)-1)
		values=[row]
		cpt=0
		if current_sheet.ncols==len(column_keys):
			logger.info("same column length %d",current_sheet.ncols)

			for col in range(current_sheet.ncols):
				values.append(current_sheet.cell(row,col).value)
				logger.info("length value %d",len(values))

				if len(column_keys)!=len(values):
					logger.critical("Mismatching number of columns and number of keys at location\n%s/sheet:%d/nrow:%d",(src_file,sheet_index,row))
				this_dict=dict(zip(column_keys,values))
				if id_col: #enforce id col type 
					if isinstance(this_dict[id_col],Number):
						this_dict[id_col]=str(int(this_dict[id_col]))
				clusters.append(this_dict)
				
		else:
			logger.info("same column length %d",current_sheet.ncols)

			if cpt!=0:
				rows_to_data.append(clusters)
			cpt+=1

	logger.info("Successfully parsed %d rows of %d values",len(rows_to_data),len(column_keys)-1)
	return rows_to_data


# normalize xp data into the measurement table 

def get_mapping(src,tgt):
	logger.info( "try to find src : %s for tgt : %s",src,tgt)
	if src==tgt:
		logger.info("src=tgt")
		return {}
	else:
		logger.info( "try to find src : %s for tgt : %s",src,tgt)
		mapping_doc=mappings_col.find_one({"src":src,"tgt":tgt})
		if len(mapping_doc)<1:
			logger.critical("cannot map dataset %s using mapping %s -> %s : Mapping not found",this_path,parser_config['id_type'],this_genome['preferred_id'])
			return None
		if "src_to_tgt" not in mapping_doc:
			logger.critical("Mapping not yet imported into db, run process_mappings.py first")
			return None
		mapping_dict=dict(mapping_doc['src_to_tgt'])
		return mapping_dict


def robust_id_mapping(id_to_map,a_map):
	if len(a_map)==0:
		#logger.info("map null")
		return id_to_map
	else:
		return a_map.get(id_to_map,[])




def cursor_to_table(c):
	all_results=list(c)
	available_first_level_keys=[]
	for r in all_results:
		for k in r.keys():
			if k not in available_first_level_keys:
				available_first_level_keys.append(k)
	tt=PrettyTable(available_first_level_keys)
	for r in all_results:
		tt.add_row([r.get(k,"NA") for k in available_first_level_keys])
	print tt

