var config_layout = {
   name: 'cose',
   idealEdgeLength: 50,
   nodeOverlap: 20,
   refresh: 20,
   fit: true,
   padding: 20,
   randomize: false,
   componentSpacing: 50,
   nodeRepulsion: 400000,
   edgeElasticity: 100,
   nestingFactor: 5,
   gravity: 80,
   numIter: 1000,
   initialTemp: 200,
   coolingFactor: 0.95,
   minTemp: 1.0
}

function draw_network(nodes, links){
    var cy = cytoscape({
    container: document.getElementById('cy'),

    elements: {
      nodes: nodes,
      edges: links
    },

    style: [ // the stylesheet for the graph
      {
        selector: 'node',
        style: {
          'background-color': '#666',
          'label': 'data(id)'
        }
      },

      {
        selector: 'edge',
        style: {
          'width': 3,
          'line-color': '#ccc',
          'target-arrow-color': '#ccc',
          'target-arrow-shape': 'triangle'
        }
      }
    ],

    layout: config_layout,
    });
    // Add specific class to the targeted node
    cy.filter(function(element, i){
      if( element.isNode() && element.data("search") === true ){
         element.addClass('class-node-origin');
      } 
    });
    cy.$('.class-node-origin').style({
      'background-color': '#000'
    }); 

    // Display node informations
    cy.on('click', 'node', function(event) {
        var node = this[0]["_private"]["data"];
        if (node.search != true){
            var html;
            // Choose between Virus symbol or Virus Uniprot ID
        if (node["virus Uniprot ID"] != undefined){
            html = '<b>Virus Uniprot ID: </b><a target="_BLANK" href="http://www.uniprot.org/uniprot/' + node["virus Uniprot ID"] + '" title="UniprotKB Swissprot and Trembl Sequences">' + node["virus Uniprot ID"] + '</a>';
        }else if (node["virus_symbol"] != undefined){
            html = '<b>Virus Symbol: </b>' + node["virus_symbol"];
        }else if (node["gene_id"] != undefined){
            html = '<b>Gene ID: </b>' + node["gene_id"];
        }
        // Display Description panel
        document.getElementById("display-info").innerHTML = '<br />' +
         '<div class="panel panel-default">' +
            '<div class="panel-heading">Description ' + node.id + '</div>' +
            '<div class="panel-body">' +
               '<div>' + html + '</div>' +
               '<div><b>Alias: </b>' + node.protein_alias_2 + '</div>' +
               '<div><b>Virus: </b>' + node.virus + '</div>' +
               '<div><b>Species: </b>' + node.species + '</div>' +
            '</div>' +
         '</div>';
      }else{
         // This is the target so descirption is already displayed
         document.getElementById("display-info").innerHTML = "";
      }
    });
    // Display edge informations
    cy.on('click', 'edge', function(event) {
        var edge = this[0]["_private"]["data"];
        var ref = [];
        // Multiple ref
        if (Array.isArray(edge.pmid)) {
            for (r in edge.pmid){
                ref.push('<a href="https://www.ncbi.nlm.nih.gov/pubmed/' + edge.pmid[r] + '">' + edge.author_name[r] + '</a>');
            }
        }else{
            ref = '<a href="https://www.ncbi.nlm.nih.gov/pubmed/' + edge.pmid + '">' + edge.author_name + '</a>';
        }

        document.getElementById("display-info").innerHTML = '<br />' +
        '<div class="panel panel-default">' +
         '<div class="panel-heading">Description</div>' +
         '<div class="panel-body">' +
            '<div><b>Source: </b>' + edge.source + '</div>' +
            '<div><b>Target: </b>' + edge.target + '</div>' +
            '<div><b>Method: </b>' + edge.method + '</div>' +
            '<div><b>Reference: </b>' + ref + '</div>' +
         '</div>' +
        '</div>';
    });

    // Check so add nodes
    $('#pp_check').on('ifChecked', function(event){
        path=document.getElementById('pp_check').getAttribute('data-path');
        element=document.getElementById('pp_check').getAttribute('data-element');
        add_pp_interactions(cy, path, element);
    });

    // Uncheck so remove nodes
    $('#pp_check').on('ifUnchecked', function(event){
        var collec = cy.$('.class-node-pp');
        cy.remove(collec);
    });

}