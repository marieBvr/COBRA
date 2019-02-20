# Database

Be carefull to follow the execution order of the scripts. 

## Feed database
```bash
cd /cobra/backend
tests/populate_with_species.py
tests/populate_with_viruses.py
tests/generate_users.py # add a pool of initial users
tests/populate_gene_ontology.py
tests/populate_with_sequences.py
tests/populate_with_samples.py
tests/populate_with_variations.py
tests/populate_with_full_mapping_table.py
tests/populate_with_mappings.py
tests/populate_with_plant-virus_interaction_data.py
tests/populate_with_plant-plant_interaction.py
tests/populate_with_string_interactions.py
tests/populate_with_QTL.py
tests/populate_with_processed_samples.py
tests/populate_with_mapping_and_orthologs_data.py


core/process_gene_ontology.py
core/process_sequences.py
core/process_expression_scoring.py
core/process_genetic_maps.py
core/process_interaction_scoring.py
core/process_orthologs.py
core/process_QTL_scoring.py
core/process_variations.py
core/process_mappings.py
core/process_full_mappings.py
core/process_pv_interactions.py
core/process_pp_interactions.py
core/process_string_interactions.py
core/process_string_interactions.py
core/process_samples.py
core/process_orthologs_monocot_plaza.py
core/process_orthologs_dicot_plaza.py
core/process_new_expression_scoring.py
core/process_new_genetic_scoring.py
core/process_new_interaction_scoring.py
tests/populate_with_markers.py
core/process_genetic_markers.py
core/process_final_scoring.py
core/process_gene_ontology.py
core/flatten_measures.py
core/flatten_measures_for_processed_data.py
```
