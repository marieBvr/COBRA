#!/usr/bin/env python
# encoding: utf-8

import sys
sys.path.append("..")
sys.path.append(".")
from config import *
from helpers.basics import load_config
from helpers.logger import Logger
from helpers.db_helpers import * 


# Script 
import datetime
if "log" not in globals():
  log = Logger.init_logger('SAMPLE_DATA_%s'%(cfg.language_code), load_config())

# clear db 

species_col.remove()
publications_col.remove()
samples_col.remove()
mappings_col.remove()
measurements_col.remove()








#### Barley

##species

barley={
	"full_name":"Hordeum vulgare",
	"abbrev_name":"H. vulgare",
	"aliases":["hordeum_vulgare","barley"],
	"taxid":4513, # taxURL: https://www.ncbi.nlm.nih.gov/Taxonomy/Browser/wwwtax.cgi?id=4513
	"wikipedia":"http://en.wikipedia.org/wiki/Barley",
	"preferred_id":"not defined",
	"classification":{
		"top_level":"Eukaryotes",
		"kingdom":	"Plantae",
		"unranked": ["Angiosperms","Monocots","Commelinids"],
		"order":	"Poales",
		"family":	"Poaceae",
		"genus":	"Pooideae",
		"species":	"H. vulgare",
	}
}
species_col.insert(barley)

## publication 
## mapping 
## samples 








#### Melon 

# species 

melon={
	"full_name":"Cucumis Melo",
	"abbrev_name":"C. melo",
	"aliases":["cucumis_melo","melon"],
	"taxid":3656, # taxURL: https://www.ncbi.nlm.nih.gov/Taxonomy/Browser/wwwtax.cgi?id=3656
	"wikipedia":"http://en.wikipedia.org/wiki/Muskmelon",
	"preferred_id":"icugi_unigene", #http://www.icugi.org/cgi-bin/ICuGI/EST/search.cgi?unigene=MU60682&searchtype=unigene&organism=melon

	"classification":{
		"top_level":"Eukaryotes",
		"kingdom":	"Plantae",
		"unranked": ["Angiosperms","Eudicots","Rosids"],
		"order":	"Cucurbitales",
		"family":	"Cucurbitaceae",
		"genus":	"Cucumis",
		"species":	"C. melo",
	}
}
species_col.insert(melon)

m_canon={
	"full_name":"Monosporascus Cannonballus",
	"abbrev_name":"M. canonballus",
	"aliases":["monosporascus_cannonballus"],
	"taxid":155416,
	"classification":{
		"top_level":"Eukaryotes",
		"kingdom":	"Fungi",
		"phylum":	"Ascomycota",
		"class":	"Sordariomycetes",
		"subclass":	"Sordariomycetidae",
		"order":	"Sordariales",
		"family":	"Incertae sedis",
		"genus":	"Monosporascus",
		"species": "M. cannonballus"
	},
	"wikipedia":"http://en.wikipedia.org/wiki/Monosporascus_cannonballus"
}
species_col.insert(m_canon)

cmv={
	"full_name":"Cucumber mosaic virus",
	"wikipedia":"http://en.wikipedia.org/wiki/Watermelon_mosaic_virus",
	"aliases":['cmv'],
	"classification":{
		"top_level":"viruses",
		"group":"Group IV ((+)ssRNA)",
		"order":"Unassigned",
		"family":"Potyviridae",
		"genus":"Potyvirus",
		"species":"Watermelon mosaic virus",
	}
	
}
species_col.insert(cmv)

wmv={
	"full_name":"Watermelon mosaic virus",
	"wikipedia":"http://en.wikipedia.org/wiki/Cucumber_mosaic_virus",
	"aliases":['wmv'],
	"classification":{
		"top_level":"viruses",
		"group":"Group IV ((+)ssRNA)",
		"order":"Unassigned",
		"family":"Bromoviridae",
		"genus":"Cucumovirus",
		"species":"Watermelon mosaic virus",
	}
}	
species_col.insert(wmv)

## publications 

melon_pub={
	"doi":"10.1186/1471-2164-10-467",
	"url":"http://www.biomedcentral.com/1471-2164/10/467",
	"first_author":"Albert Mascarell-Creus",
	"pmid":19821986, # http://www.ncbi.nlm.nih.gov/pubmed/19821986
	# rest can be populated automatically 
}
publications_col.insert(melon_pub)

melon_pub={
    "doi":"10.1094/MPMI-07-11-0193",
    "url":"http://dx.doi.org/10.1094/MPMI-07-11-0193",
    "first_author":"Daniel Gonzalez-Ibeas",
    "pmid":21970693, # http://www.ncbi.nlm.nih.gov/pubmed/19821986
    # rest can be populated automatically 
}
publications_col.insert(melon_pub)

## mapping 

mapping_table={
	"data_file":"mappings/1471-2164-13-601-s7.xls",
	"src":"est_unigen",
	"src_version":"4.0",
	"tgt":"icugi_unigene",
	"tgt_version":"tomato200607#2",
	"url":"http://www.biomedcentral.com/content/supplementary/1471-2164-13-601-s7.xls",
	"doi":"10.1186/1471-2164-13-601-s7",
	"key":"est_2_unigene",
	# parser config 
		# xls parser configuration, are propagated to all entries in  "experimental_results",
	"xls_parsing":{
		"n_rows_to_skip":3,
		"column_keys":['idx','est_unigen','sequence','icugi_unigene'],
		"sheet_index":0,
	}
}
mappings_col.insert(mapping_table)

## samples 

samples={
    "src_pub":23134692, # Any field from the pub, doi, pmid, first author etc. 
    "species":"C. melo", # any abbrev name, key or full name, 
    "name":"Root transcriptional responses of two melon genotypes with contrasting resistance to Monosporascus cannonballus (Pollack et Uecker) infection",
    "comments":[
        {"content":"""Monosporascus cannonballus is the main causal agent of melon vine 
        decline disease. Several studies have been carried out mainly focused on the study 
        of the penetration of this pathogen into melon roots, the evaluation of symptoms 
        severity on infected roots, and screening assays for breeding programs. However, 
        a detailed molecular view on the early interaction between M. cannonballus and melon 
        roots in either susceptible or resistant genotypes is lacking. In the present study, 
        we used a melon oligo-based microarray to investigate the gene expression responses 
        of two melon genotypes, Cucumis melo ‘Piel de sapo’ (‘PS’) and C. melo ‘Pat 81’, with 
        contrasting resistance to the disease. This study was carried out at 1 and 3 days after 
        infection (DPI) by M. cannonballus. """}
    ],
    "assay":{
        "type":"micro-array",
        "design":"http://www.ebi.ac.uk/arrayexpress/files/A-MEXP-2252/A-MEXP-2252.adf.txt"
    },
    "deposited":{
        "repository":"http://www.ebi.ac.uk/arrayexpress/experiments/E-MEXP-3732/",
        "sample_description_url":"http://www.ebi.ac.uk/arrayexpress/experiments/E-MEXP-3732/samples/",
        "experimental_meta_data":"http://www.ebi.ac.uk/arrayexpress/xml/v2/experiments?query=melogen_melo_v1"

    },
    # xls parser configuration, are propagated to all entries in  "experimental_results",
    "xls_parsing":{
		"n_rows_to_skip":3,
		"column_keys":['idx','est_unigen','description','fold_change','function'],
		"sheet_index":0,
		"id_type":"est_unigen"
	},
    "experimental_results":[
        {
			"data_file":"Cucumis/cucumis_melo/transcriptomics/micro_array/monosporascus_cannonballus/condition1/1471-2164-13-601-s1.xls",
			"conditions":["non infected",{
				"infected":True,
				"infection_agent":"monosporascus_cannonballus",
				"label":"Infected with M. canonballus"
				}
			],
			"contrast":"inoculated VS non infected",
			"type":"contrast",
			"variety":"PS",
			"day_after_inoculation":1,
            "material":"undefined"
		},
		{
			"data_file":"Cucumis/cucumis_melo/transcriptomics/micro_array/monosporascus_cannonballus/condition1/1471-2164-13-601-s2.xls",
			"conditions":["non infected",{
				"infected":True,
				"infection_agent":"monosporascus_cannonballus",
				"label":"Infected with M. canonballus"
				}
			],
			"contrast":"inoculated VS non infected",
			"type":"contrast",
			"variety":"pat81",
			"day_after_inoculation":1,
            "material":"undefined"
		},
		{
			"data_file":"Cucumis/cucumis_melo/transcriptomics/micro_array/monosporascus_cannonballus/condition1/1471-2164-13-601-s3.xls",
			"conditions":["non infected",{
				"infected":True,
				"infection_agent":"monosporascus_cannonballus",
				"label":"Infected with M. canonballus"
				}
			],
			"contrast":"inoculated VS non infected",
			"type":"contrast",
			"variety":"PS",
			"day_after_inoculation":3,
            "material":"undefined"
		},
        {
            "data_file":"Cucumis/cucumis_melo/transcriptomics/micro_array/monosporascus_cannonballus/condition1/1471-2164-13-601-s4.xls",
			"conditions":["non infected",{
				"infected":True,
				"infection_agent":"monosporascus_cannonballus",
				"label":"Infected with M. canonballus"
				}
			],
			"contrast":"inoculated VS non infected",
			"type":"contrast",
			"variety":"pat81",
			"day_after_inoculation":3,
            "material":"undefined"
        }
    ]

}
samples_col.insert(samples)


samples={
    "src_pub":21970693, # Any field from the pub, doi, pmid, first author etc. 
    "species":"C. melo", # any abbrev name, key or full name, 
    "name":"Recessive resistance to Watermelon mosaic virus in melon is associated with a defense response as revealed by microarray analysis",
    "comments":[
        {"content":"""Two melon genotypes have been used to analyse transcriptomic 
        responses to infection by Watermelon mosaic 
        virus: Tendral (susceptibel to WMV) and TGR-1551 (resistant to WMV). 
        For each genotype, 60 melon seedlings were inoculated with WMV-M116 
        and another 60 were mock-inoculated. Cotyledons of 10 plants were 
        harvested at 1, 3, 5, 7, 9 and 15 dpi. At 15 dpi, 
        the systemically infected second true leaf was also harvested. 
        To reduce variability, each biological replicate used in this study 
        was prepared by mixing the RNA extracts from 2 or 4 mock or WMV-inoculated 
        cotyledons, respectively, or from 3 melon leaves. Samples (WMV infected and 
        mock inoculated) corresponding to cotyledons at 1, 3 and 7 dpi, and leaves at 
        15 dpi were used for microarray hybridisations, three biological replicates 
        for each one, leading to a total of 48 samples."""}
    ],
    "assay":{
        "type":"micro-array",
        "design":"https://www.ebi.ac.uk/arrayexpress/files/A-GEOD-13748/A-GEOD-13748.adf.txt"
    },
    "deposited":{
        "repository":"https://www.ebi.ac.uk/arrayexpress/experiments/E-GEOD-30111/",
        "sample_description_url":"https://www.ebi.ac.uk/arrayexpress/experiments/E-GEOD-30111/samples/",
        "experimental_meta_data":"http://www.ebi.ac.uk/arrayexpress/xml/v2/experiments?query=melogen_melo_v1"

    },
    # xls parser configuration, are propagated to all entries in  "experimental_results",
    "xls_parsing":{
        "n_rows_to_skip":1,
        "column_keys":['idx','est_unigen','adjP_Val','P_Value','t','B','logFC','SPOT_ID'],
        "sheet_index":0,
        "id_type":"est_unigen"
    },
    "experimental_results":[
        {
            "data_file":"Cucumis/cucumis_melo/transcriptomics/micro_array/watermelon_mosaic_virus/cotyledon_Tendral-Mock_1dpi_VS_cotyledon_Tendral-WMVinfected_1dpi.xls",
            "conditions":["non infected",{
                "infected":True,
                "infection_agent":"Watermelon mosaic virus",
                "label":"Infected with WMV"
                
                
                }
            ],
            "contrast":"inoculated VS non infected",
            "type":"contrast",
            "variety":"Tendral",
            "day_after_inoculation":1,
            "material":"cotyledon"
        },
        {
            "data_file":"Cucumis/cucumis_melo/transcriptomics/micro_array/watermelon_mosaic_virus/cotyledon_Tendral-Mock_3dpi_VS_cotyledon_Tendral-WMVinfected_3dpi.xls",
            "conditions":["non infected",{
                "infected":True,
                "infection_agent":"Watermelon mosaic virus",
                "label":"Infected with WMV"
                
                
                }
            ],
            "contrast":"inoculated VS non infected",
            "type":"contrast",
            "variety":"Tendral",
            "day_after_inoculation":3,
            "material":"cotyledon"
        },
        {
            "data_file":"Cucumis/cucumis_melo/transcriptomics/micro_array/watermelon_mosaic_virus/cotyledon_Tendral-Mock_7dpi_VS_cotyledon_Tendral-WMVinfected_7dpi.xls",
            "conditions":["non infected",{
                "infected":True,
                "infection_agent":"Watermelon mosaic virus",
                "label":"Infected with WMV"
                
                }
            ],
            "contrast":"inoculated VS non infected",
            "type":"contrast",
            "variety":"Tendral",
            "day_after_inoculation":7,
            "material":"cotyledon"
        },
        {
            "data_file":"Cucumis/cucumis_melo/transcriptomics/micro_array/watermelon_mosaic_virus/cotyledon_TGR-1551-Mock_1dpi_VS_cotyledon_TGR1551-WMVinfected_1dpi.xls",
            "conditions":["non infected",{
                "infected":True,
                "infection_agent":"Watermelon mosaic virus",
                "label":"Infected with WMV"
                
                }
            ],
            "contrast":"inoculated VS non infected",
            "type":"contrast",
            "variety":"TGR1551",
            "day_after_inoculation":1,
            "material":"cotyledon"
        },
        {
            "data_file":"Cucumis/cucumis_melo/transcriptomics/micro_array/watermelon_mosaic_virus/cotyledon_TGR-1551-Mock_3dpi_VS_cotyledon_TGR1551-WMVinfected_3dpi.xls",
            "conditions":["non infected",{
                "infected":True,
                "infection_agent":"Watermelon mosaic virus",
                "label":"Infected with WMV"
                
                }
            ],
            "contrast":"inoculated VS non infected",
            "type":"contrast",
            "variety":"TGR1551",
            "day_after_inoculation":3,
            "material":"cotyledon"
        },
        {
            "data_file":"Cucumis/cucumis_melo/transcriptomics/micro_array/watermelon_mosaic_virus/cotyledon_TGR-1551-Mock_7dpi_VS_cotyledon_TGR1551-WMVinfected_7dpi.xls",
            "conditions":["non infected",{
                "infected":True,
                "infection_agent":"Watermelon mosaic virus",
                "label":"Infected with WMV"
                
                }
            ],
            "contrast":"inoculated VS non infected",
            "type":"contrast",
            "variety":"TGR1551",
            "day_after_inoculation":7,
            "material":"cotyledon"
        },
        {
            "data_file":"Cucumis/cucumis_melo/transcriptomics/micro_array/watermelon_mosaic_virus/leaf_Tendral-Mock_15dpi_VS_leaf_Tendral-WMVinfected_15dpi.xls",
            "conditions":["non infected",{
                "infected":True,
                "infection_agent":"Watermelon mosaic virus",
                "label":"Infected with WMV"
                
                }
            ],
            "contrast":"inoculated VS non infected",
            "type":"contrast",
            "variety":"Tendral",
            "day_after_inoculation":15,
            "material":"leaf"
        },
        {
            "data_file":"Cucumis/cucumis_melo/transcriptomics/micro_array/watermelon_mosaic_virus/leaf_TGR-1551-Mock_15dpi_VS_leaf_TGR1551-WMVinfected_15dpi.xls",
            "conditions":["non infected",{
                "infected":True,
                "infection_agent":"Watermelon mosaic virus",
                "label":"Infected with WMV"
                
                }
            ],
            "contrast":"inoculated VS non infected",
            "type":"contrast",
            "variety":"TGR1551",
            "day_after_inoculation":15,
            "material":"leaf"
        }
    ]

}
samples_col.insert(samples)

samples={
	"src_pub":19821986, # Any field from the pub, doi, pmid, first author etc. 
	"species":"C. melo", # any abbrev name, key or full name, 
	"name":"Transcriptomics of infection of C. melo",
	"comments":[
		{"content":"""
Excerpt from the article related to strains : 

The material used for the transcriptomic profile analyses came from three different C. melo accessions: the line T-111, which corresponds to a Piel de Sapo breeding line, the Tendral variety (Semillas Fitó, Barcelona, Spain), and the pat81 accession of C. melo L. ssp. agrestis (Naud.) Pangalo maintained at the germplasm bank of COMAV (COMAV-UPV, Valencia, Spain). Seeds of T-111 were germinated at 30°C for two days and plants were grown in a greenhouse in peat bags, drip irrigated, with 0.25-m spacing between plants. Fruits were collected 15 and 46 days after pollination and mesocarp tissues were used for RNA extractions. Roots from pat81 plants were mock treated or inoculated with 50 colony-forming units (CFU) of M.c. per gram of sterile soil and harvested 14 days after inoculation. Cotyledons from var. Tendral, mock treated or mechanically inoculated with CMV, were harvested 3 days post inoculation. Plant growth and infections were done as described in González-Ibeas et al. [1].
		""","author":"hayssam","date":datetime.datetime.now()}
	],
	"assay":{
		"type":"micro-array",
		"design":"http://www.ebi.ac.uk/arrayexpress/files/A-MEXP-1675/A-MEXP-1675.adf.txt"
	},
	"deposited":{
		"repository":"http://www.ebi.ac.uk/arrayexpress/experiments/E-MEXP-2334/",
		"sample_description_url":"http://www.ebi.ac.uk/arrayexpress/experiments/E-MEXP-2334/samples/",
		"experimental_meta_data":"http://www.ebi.ac.uk/arrayexpress/xml/v2/experiments?query=melogen_melo_v1"

	},
	# xls parser configuration, are propagated to all entries in  "experimental_results",
	"xls_parsing":{
		"n_rows_to_skip":4,
		"column_keys":['idx','est_unigen','length','description','fold_change'],
		"sheet_index":0,
		"id_type":"est_unigen"
	},
	"experimental_results":[
		{
			"data_file":"Cucumis/cucumis_melo/transcriptomics/micro_array/no_infection/condition_15d_VS_46d/1471-2164-10-467-S1.XLS",
			"conditions":["15d","46d"],
			"contrast":"46d VS 15d",
			"type":"contrast",
			"variety":"T-111"
		},
		{
			"data_file":"Cucumis/cucumis_melo/transcriptomics/micro_array/monosporascus_cannonballus/1471-2164-10-467-S2.XLS",
			"conditions":["non infected",{
				"infected":True,
				"infection_agent":"monosporascus_cannonballus",
				"label":"Infected with M. canonballus"
				}
			],
			"contrast":"inoculated VS non infected",
			"type":"contrast",
			"variety":"pat81"
		},
		{
			"data_file":"Cucumis/cucumis_melo/transcriptomics/micro_array/cucumber_mosaic_virus/1471-2164-10-467-S3.XLS",
			"conditions":["non infected",{
				"infected":True,
				"infection_agent":"Cucumber mosaic virus",
				"label":"Infected with CMV"
				}
			],
			"contrast":"inoculated VS non infected",
			"type":"contrast",
			"variety":"Tendral"
			
		}
	]

}
samples_col.insert(samples)








#### Prunus 

##species
prunus={
	"full_name":"Prunus Domestica",
	"abbrev_name":"P. domestica",
	"aliases":["prunus_domestica","prunus"],
	"taxid":3758, # taxURL: https://www.ncbi.nlm.nih.gov/Taxonomy/Browser/wwwtax.cgi?id=3656
	"wikipedia":"http://en.wikipedia.org/wiki/Prunus_domestica",
	"preferred_id":"unigene",
	"classification":{
		"top_level":"Eukaryotes",
		"kingdom":	"Plantae",
		"unranked":	["Angiosperms","Eudicots","Rosids"],
		"order":	"Rosales",
		"family":	"Rosaceae",
		"genus":	"Prunus",
		"subgenus":	"Prunus",
		"section":	"Prunus",
		"species":	"P. domestica",
	}
}
species_col.insert(prunus)

ppox={
	"full_name":"Plum pox",
	"wikipedia":"http://en.wikipedia.org/wiki/Plum_pox",
	"aliases":['sharka','ppv'],
	"classification":{
		"top_level":"viruses",
		"group": "Group IV ((+)ssRNA)",
		"family": "Potyviridae",
		"genus": "Potyvirus",
		"species": "Plum pox virus"
	}
	
}
species_col.insert(ppox)

##publications
prunus_pub={
	"doi":"10.1371/journal.pone.0100477",
	"url":"http://journals.plos.org/plosone/article?id=10.1371/journal.pone.0100477",
	"first_author":"Bernardo Rodamilans",
	"title":"Transcriptomic Analysis of Prunus domestica Undergoing Hypersensitive Response to Plum Pox Virus Infection",
	"pmid":24959894, # http://www.ncbi.nlm.nih.gov/pubmed/24959894
	# rest can be populated automatically 
}
publications_col.insert(prunus_pub)

##mappings

##samples
prunus_samples={
	"src_pub":19821986, # Any field from the pub, doi, pmid, first author etc. 
	"species":"P. domestica", # any abbrev name, key or full name, 
	"strain":"JoJo",
	"name":"Transcriptomics of infection of P. domestica Jojo by PPV",
	"xp_page":{
	"content":"""
RNAseq from P.domestica cv Jojo infected and non-infected with PPV; 
====

3020 differentially expressed unigenes (2234 up- and 786 down-regulated); 154 potential resistance genes; 75 additional genes that might be good candidates based on literature findings.

paper:

[Transcriptomic Analysis of Prunus domestica Undergoing Hypersensitive Response to Plum Pox Virus Infection](#24959894)
url:
http://www.plosone.org/article/info%3Adoi%2F10.1371%2Fjournal.pone.0100477

data linked with articles :

[journal.pone.0100477.s004.xls](#journal.pone.0100477.s004.xls)
Differentially expressed unigenes. The raw number of reads found on each sample is given in columns Jojo-W1, Jojo-W2, Jojo-W+PPV and Jojo-M+PPV. Fold changes between infected and non-infected samples and FDR values are detailed in columns FC and FDR, respectively. Column labeled Description expands on the details of each unigene, including length in nucleotides.
doi:10.1371/journal.pone.0100477.s004 (XLS)

[journal.pone.0100477.s005.xls](#journal.pone.0100477.s005.xls)
Differentially expressed unigenes found in PRGdb. The raw number of reads found on each sample is given in columns Jojo-W1, Jojo-W2, Jojo-W+PPV and Jojo-M+PPV. Fold changes between infected and non-infected samples and FDR values are detailed in columns FC and FDR, respectively. Column labeled Description expands on the details of each unigene, including length in nucleotides.
doi:10.1371/journal.pone.0100477.s005. Content of this table is the same as the content from table s004, but only genes found in PRGdb are included (156 genes)

[journal.pone.0100477.s006.xls](#journal.pone.0100477.s006.xls)
Genes possibly involved in plant defense not included in the PRGdb. Similarity to a known protein is given in the Homology column. The raw number of reads found on each sample is given in columns Jojo-W1, Jojo-W2, Jojo-W+PPV and Jojo-M+PPV. Fold changes between infected and non-infected samples and FDR values are detailed in columns FC and FDR, respectively. Column labeled Description expands on the details of each unigene, including length in nucleotides.
doi:10.1371/journal.pone.0100477.s006

[journal.pone.0100477.s007.xls](#journal.pone.0100477.s007.xls)
List of genes from PRGdb matched by more than one ‘Jojo’ unigene. Reference numbers of the matched genes are shown in the NCBI Match column. The raw number of reads found on each sample is shown in columns Jojo-W1, Jojo-W2, Jojo-W+PPV and Jojo-M+PPV. Fold changes between infected and non-infected samples is given in column FC. Column labeled Description expands on the details of each gene.
doi:10.1371/journal.pone.0100477.s007. Not included.
	"""},
	"assay":{"type":"RNA-Seq"},
	"xls_parsing":{
		"n_rows_to_skip":2,
		"column_keys":['idx',"unigene", "Sequence", "Jojo-W1", "Jojo-W2", "Jojo-W+PPV", "Jojo-M+PPV", "fold_change", "logFC", "logCPM", "PValue", "FDR", "Description", "AccPrunus", "DescPrunus", "AccNCBI", "DescNCBI", "AccPlants", "DescPlants", "AccPRG", "DescPRG"],
		"sheet_index":0,
		"id_type":"unigene" #**TODO** check ID type for prunus 
	},
	"experimental_results":[{
		"name":"journal.pone.0100477.s004.xls",
		"data_file":"Prunus/prunus_domestica/transcriptomics/rna-seq/plum_pox_virus/journal.pone.0100477.s004.xls",
		"description":"Differentially expressed unigenes",
		"type":"contrast",
		"conditions":[
			"non-infected",
			{"infected":True,"infection_agent":"ppv","label":"infected with PPV"}
		]
	}, {
		"name":"journal.pone.0100477.s005.xls",
		"data_file":"Prunus/prunus_domestica/transcriptomics/rna-seq/plum_pox_virus/journal.pone.0100477.s005.xls",
		"description":"Differentially expressed unigenes found in PRGdb",
		"type":"contrast",
		"conditions":[
			"non-infected",
			{"infected":True,"infection_agent":"ppv","label":"infected with PPV"}
		]
	}
	# , {
	# 	"name":"journal.pone.0100477.s006.xls",
	# 	"data_file":"Prunus/prunus_domestica/transcriptomics/rna-seq/plum_pox_virus/journal.pone.0100477.s006.xls",
	# 	"description":"Genes that might be involved in plant defense and are not included in the PRGdb",
	# 	"type":"contrast",
	# 	"conditions":[
	# 		"non-infected",
	# 		{"infected":True,"infection_agent":"ppv","label":"infected with PPV"}
	# 	],
	# 	"xls_parsing":{'column_keys':['idx',"Homology", "Jojo-W1", "Jojo-W2", "Jojo-W+PPV", "Jojo-M+PPV", "fold_change", "logFC", "logCPM", "PValue", "FDR", "Description"]}
	# }
	# Table S7 is weird in content
	# , {
	# 	"name":"journal.pone.0100477.s007.xls",
	# 	"data_file":"Prunus/prunus_domestica/transcriptomics/rna-seq/plum_pox_virus/journal.pone.0100477.s007.xls",
	# 	"description":"",
	# 	"conditions":[
	# 		"non-infected",
	# 		{"infected":True,"infection_agent":"ppv","label":"infected with PPV"}
	# 	]
	# }
	]
}
samples_col.insert(prunus_samples)


####Arabidopsis

arabidopsis_thaliana={
	"full_name":"Arabidopsis thaliana",
	"abbrev_name":"A. Thaliana",
	"aliases":["thale cress", "mouse-ear cress","arabidopsis"],
	"taxid":3702, # taxURL: https://www.ncbi.nlm.nih.gov/Taxonomy/Browser/wwwtax.cgi?id=4081
	"wikipedia":"http://en.wikipedia.org/wiki/Arabidopsis_thaliana",
	"preferred_id":"AGI_TAIR",
	"classification":{
		"top_level":"Eukaryotes",
		"kingdom":	"Plantae",
		"unranked": ["Angiosperms","Eudicots","Rosids"],
		"order":	"Brassicales",
		"family":	"Brassicaceae",
		"genus":	"Arabidopsis",
		"species":	"A. Thaliana",
	}
}
species_col.insert(arabidopsis_thaliana)

## mapping 

mapping_table={
	"data_file":"mappings/CATMA_2.3_07122011.xls",
	"src":"CATMA_ID",
	"src_version":"CATMA V2.1",
	"tgt":"AGI_TAIR",
	"tgt_version":"2_3",
	"url":"none",
	"doi":"none",
	"key":"CATMA_2_AGI",
	# parser config 
		# xls parser configuration, are propagated to all entries in  "experimental_results",
	"xls_parsing":{
		"n_rows_to_skip":4,
		"column_keys":['idx','CATMA_ID','Probe_type','AGI_TAIR','Gene_type','Description'],
		"sheet_index":0,
	}
}
mappings_col.insert(mapping_table)



samples={
	"src_pub":"not published yet", # Any field from the pub, doi, pmid, first author etc. 
	"species":"Arabidopsis thaliana", # any abbrev name, key or full name, 
	"name":"potyvirus-Transcriptional analysis of Arabidopsis thaliana infected by a potyvirus. (thale cress)",
	"comments":[
		{"content":""" not published yet""","author":"Frederic Revers","date":datetime.datetime.now()}
	],
	"assay":{
		"type":"micro-array",
		"design":"CATMA V2.1"
	},
	"deposited":{
		"repository":"http://www.ncbi.nlm.nih.gov/geo/query/acc.cgi?acc=GSE8875",
		"sample_description_url":"http://urgv.evry.inra.fr/cgi-bin/projects/CATdb/consult_expce.pl?experiment_id=37",
		"experimental_meta_data":"not deposited yet"

	},
	# xls parser configuration, are propagated to all entries in  "experimental_results",
	"xls_parsing":{
		"n_rows_to_skip":1,
		"column_keys":['idx','CATMA ID','AGI CODE','FUNCTION','TYPE_QUAL','PCR_RESULT','I S1','I S2','R','P-VAL'],
		"sheet_index":0,
		"id_type":"CATMA_ID"
	},
	"experimental_results":[
		
		{
			"data_file":"Arabidopsis/arabidopsis_thaliana/transcriptomics/microarray/tobacco_etch_viruses/2/RA03-02_Potyvirus_exp37_dyeswap1_1_iAino_iAsys.xls",
			"conditions":["non infected",{
				"infected":True,
				"infection_agent":"Tobacco etch virus",
				"label":"Infected with TEV"
				}
			],
			"contrast":"inoculated VS non infected",
			"type":"contrast",
			"variety":"landsberg erecta",
			"day_after_inoculation":7,
            "material":"leaf"
			
		},
		{
			"data_file":"Arabidopsis/arabidopsis_thaliana/transcriptomics/microarray/tobacco_etch_viruses/2/RA03-02_Potyvirus_exp37_dyeswap1_2_iAino_iAsys.xls",
			"conditions":["non infected",{
				"infected":True,
				"infection_agent":"Tobacco etch virus",
				"label":"Infected with TEV"
				}
			],
			"contrast":"inoculated VS non infected",
			"type":"contrast",
			"variety":"landsberg erecta",
			"day_after_inoculation":7,
            "material":"leaf"
			
		},
		{
			"data_file":"Arabidopsis/arabidopsis_thaliana/transcriptomics/microarray/tobacco_etch_viruses/2/RA03-02_Potyvirus_exp37_dyeswap3_1_iAsys_mAsys.xls",
			"conditions":["non infected",{
				"infected":True,
				"infection_agent":"Tobacco etch virus",
				"label":"Infected with TEV"
				}
			],
			"contrast":"inoculated VS non infected",
			"type":"contrast",
			"variety":"landsberg erecta",
			"day_after_inoculation":7,
            "material":"leaf"
			
		},
		{
			"data_file":"Arabidopsis/arabidopsis_thaliana/transcriptomics/microarray/tobacco_etch_viruses/2/RA03-02_Potyvirus_exp37_dyeswap2_1_iAino_mAino.xls",
			"conditions":["non infected",{
				"infected":True,
				"infection_agent":"Tobacco etch virus",
				"label":"Infected with TEV"
				}
			],
			"contrast":"inoculated VS non infected",
			"type":"contrast",
			"variety":"landsberg erecta",
			"day_after_inoculation":7,
            "material":"leaf"
			
		},
		{
			"data_file":"Arabidopsis/arabidopsis_thaliana/transcriptomics/microarray/tobacco_etch_viruses/2/RA03-02_Potyvirus_exp37_dyeswap2_2_iAino_mAino.xls",
			"conditions":["non infected",{
				"infected":True,
				"infection_agent":"Tobacco etch virus",
				"label":"Infected with TEV"
				}
			],
			"contrast":"inoculated VS non infected",
			"type":"contrast",
			"variety":"landsberg erecta",
			"day_after_inoculation":7,
            "material":"leaf"
			
		},
		{
			"data_file":"Arabidopsis/arabidopsis_thaliana/transcriptomics/microarray/tobacco_etch_viruses/2/RA03-02_Potyvirus_exp37_dyeswap3_2_iAsys_mAsys.xls",
			"conditions":["non infected",{
				"infected":True,
				"infection_agent":"Tobacco etch virus",
				"label":"Infected with TEV"
				}
			],
			"contrast":"inoculated VS non infected",
			"type":"contrast",
			"variety":"landsberg erecta",
			"day_after_inoculation":7,
            "material":"leaf"
			
		}
	]

}
samples_col.insert(samples)
####Tomato

##species
tomato={
	"full_name":"Solanum lycopersicum",
	"abbrev_name":"S. Lycopersicum",
	"aliases":["Lycopersicon lycopersicum","Solanum lycopersicum L.","tomato"],
	"taxid":4081, # taxURL: https://www.ncbi.nlm.nih.gov/Taxonomy/Browser/wwwtax.cgi?id=4081
	"wikipedia":"http://en.wikipedia.org/wiki/Tomato",
	"preferred_id":"SGN_U",
	"classification":{
		"top_level":"Eukaryotes",
		"kingdom":	"Plantae",
		"unranked": ["Angiosperms","Eudicots","Asterids"],
		"order":	"Solanales",
		"family":	"Solanaceae",
		"genus":	"Solanum",
		"species":	"S. Lycopersicum",
	}
}
species_col.insert(tomato)

tev={
	"full_name":"Tobacco etch virus",
	"wikipedia":"http://en.wikipedia.org/wiki/Tobacco_etch_virus",
	"aliases":['tev'],
	"classification":{
		"top_level":"viruses",
		"group":"Group IV ((+)ssRNA)",
		"order":"Unassigned",
		"family":"Potyviridae",
		"genus":"Potyvirus",
		"species":"Tobacco etch virus",
	}
	
}
species_col.insert(tev)

## mapping 

mapping_table={
	"data_file":"mappings/tomato_species_unigenes.v2.Solyc_ITAG2.3.genemodels.map.annot.xls",
	"src":"SGN_U",
	"src_version":"tomato200607#2",
	"tgt":"ITAG",
	"tgt_version":"2_3",
	"url":"none",
	"doi":"none",
	"key":"SGN_U_2_ITAG",
	# parser config 
		# xls parser configuration, are propagated to all entries in  "experimental_results",
	"xls_parsing":{
		"n_rows_to_skip":0,
		"column_keys":['idx','SGN_U','ITAG','InterproDescription','GOAccession'],
		"sheet_index":0,
	}
}
mappings_col.insert(mapping_table)


mapping_table={
	"data_file":"mappings/TOM1_id_to_Current_unigene.xls",
	"src":"SGN_S",
	"src_version":"tom1",
	"tgt":"SGN_U",
	"tgt_version":"tomato200607#2",
	"url":"none",
	"doi":"none",
	"key":"SGN_S_2_SGN_U",
	# parser config 
		# xls parser configuration, are propagated to all entries in  "experimental_results",
	"xls_parsing":{
		"n_rows_to_skip":1,
		"column_keys":['idx','SGN_S','SGN_U'],
		"sheet_index":0,
	}
}
mappings_col.insert(mapping_table)

##samples
samples={
	"src_pub":"not published yet", # Any field from the pub, doi, pmid, first author etc. 
	"species":"Solanum lycopersicum", # any abbrev name, key or full name, 
	"name":"Transcriptomics of infection of S.lycopersicum",
	"comments":[
		{"content":""" not published yet""","author":"ben","date":datetime.datetime.now()}
	],
	"assay":{
		"type":"micro-array",
		"design":"tom1"
	},
	"deposited":{
		"repository":"not deposited yet",
		"sample_description_url":"not deposited yet",
		"experimental_meta_data":"not deposited yet"

	},
	# xls parser configuration, are propagated to all entries in  "experimental_results",
	"xls_parsing":{
		"n_rows_to_skip":1,
		"column_keys":['idx','SGN_S','Block','Column','Row','ID','logFC','A','t','P_Value','B','5_unigen_build_1_2','5_GO_annotation','3_unigen_build_1_2','3_GO_annotation'],
		"sheet_index":0,
		"id_type":"SGN_S"
	},
	"experimental_results":[
		
		{
			"data_file":"Solanum/solanum_lycopersicum/transcriptomics/micro_array/tobacco_etch_virus/toptable_ino7dpi.xls",
			"conditions":["non infected",{
				"infected":True,
				"infection_agent":"Tobacco etch virus",
				"label":"Infected with TEV"
				}
			],
			"contrast":"inoculated VS non infected",
			"type":"contrast",
			"variety":"unclear"
			
		}
	]

}
samples_col.insert(samples)

## publication 

#tomato_pub={
#	"doi":"10.1186/1471-2164-10-467",
#	"url":"http://www.biomedcentral.com/1471-2164/10/467",
#	"first_author":"Albert Mascarell-Creus",
#	"pmid":19821986, # http://www.ncbi.nlm.nih.gov/pubmed/19821986
#	# rest can be populated automatically 
#}
