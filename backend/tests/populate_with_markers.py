#!/usr/bin/env python
# encoding: utf-8

import sys
sys.path.append("..")
sys.path.append(".")
from config import *
from helpers.basics import load_config
from helpers.logger import Logger
from helpers.db_helpers import * 
import string
from random import *

# Script 
import datetime
if "log" not in globals():
  log = Logger.init_logger('SAMPLE_DATA_%s'%(cfg.language_code), load_config())

# Clear collections to fill



genetic_markers_col.drop()






###################################################################################################################
############################################ PRUNUS PERSICA #######################################################
###################################################################################################################

#marker_table={
#	"data_file":"Prunus/prunus_persica/genetic_markers/persica_markers_final.tsv",
#	"species":"Prunus persica",
#	"src":"Marker ID",
#	"src_version":"",
#        "tgt":"Map ID",
#	"url":"",
#	"doi":"none",
#	"key":"",
#	# parser config 
#		# xls parser configuration, are propagated to all entries in  "experimental_results",
#	"xls_parsing":{
#		"n_rows_to_skip":1,
#		"column_keys":['idx','HREF_markers','Marker ID','Alias','Type','Species','Map ID','Linkage Group','Start','Stop','Chromosome','Position','Location','Citation','Primer1 name','Primer1 sequence','Primer2 name','Primer2 sequence'],
#		"sheet_index":0,
#	}
#}
#genetic_markers_col.insert(marker_table)

###################################################################################################################
############################################ PRUNUS ARMENIACA #####################################################
###################################################################################################################

#marker_table={
#	"data_file":"Prunus/prunus_armeniaca/genetic_markers/armeniaca_markers_final.tsv",
#	"species":"Prunus armeniaca",
#	"src":"Marker ID",
#	"src_version":"",
#       "tgt":"Map ID",
#	"url":"",
#	"doi":"none",
#	"key":"",
#	# parser config 
#		# xls parser configuration, are propagated to all entries in  "experimental_results",
#	"xls_parsing":{
#		"n_rows_to_skip":1,
#		"column_keys":['idx','HREF_markers','Marker ID','Alias','Type','Species','Map ID','Linkage Group','Start','Stop','Location','Citation','Primer1 name','Primer1 sequence','Primer2 name','Primer2 sequence'],
#		"sheet_index":0,
#	}
#}
#genetic_markers_col.insert(marker_table)

###################################################################################################################
############################################ PRUNUS ###############################################################
###################################################################################################################

marker_table={
	"data_file":"Prunus/genetic_markers/prunus_species_genetics_markers.tsv",
	"src":"Marker ID",
	"src_version":"",
        "tgt":"Map ID",
	"url":"",
	"doi":"none",
	"key":"",
	# parser config 
		# xls parser configuration, are propagated to all entries in  "experimental_results",
	"xls_parsing":{
		"n_rows_to_skip":1,
		"column_keys":['idx','HREF_markers','Marker ID','Alias','Type','HREF_species','Species','Map ID','Linkage Group','StartcM','StopcM','Chromosome','Start','End','Citation','Primer1 name','Primer1 sequence','Primer2 name','Primer2 sequence','Primer3 name','Primer3 sequence','Primer4 name','Primer4 sequence'],
		"sheet_index":0,
	}
}
genetic_markers_col.insert(marker_table)



###################################################################################################################
############################################ CUCUMIS MELO #########################################################
###################################################################################################################

marker_table={
	"data_file":"Cucumis/genetic_markers/physical_and_genetic_maps.tsv",
	"src":"Marker ID",
	"src_version":"Melonomics",
        "species":"Cucumis melo",
        "tgt":"Start",
	"url":"",
	"doi":"none",
	"key":"",
	# parser config 
		# xls parser configuration, are propagated to all entries in  "experimental_results",
	"xls_parsing":{
		"n_rows_to_skip":0,
		"column_keys":['idx','Marker ID','Type','Chromosome','Start','cM Garcia-Mas et al (2012)','cM Argyris et al (2015)','LG_ICuGI','cM_ICuGI','Scaffold_v3_5_1','Scaffold coordinates'],
		"sheet_index":0,
	}
}
genetic_markers_col.insert(marker_table)





#marker_table={
#	"data_file":"Arabidopsis/genetic_markers/TAIR_genetic_markers.tsv",
#	"src":"Marker ID",
#	"src_version":"",
#       "tgt":"Map ID",
#	"url":"",
#	"doi":"none",
#	"key":"",
#	# parser config 
#		# xls parser configuration, are propagated to all entries in  "experimental_results",
#	"xls_parsing":{
#		"n_rows_to_skip":1,
#		"column_keys":['idx','HREF_markers','Marker ID','Alias','Type','HREF_species','Species','Map ID','Linkage Group','StartcM','StopcM','Chromosome','Start','End','Citation','Primer1 name','Primer1 sequence','Primer2 name','Primer2 sequence','Primer3 name','Primer3 sequence','Primer4 name','Primer4 sequence'],
#		"sheet_index":0,
#	}
#}
#genetic_markers_col.insert(marker_table)




