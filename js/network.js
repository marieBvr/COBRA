function draw_network(nodes, links){
	network = {};
	network.links = links;
	network.nodes = nodes;
	network = JSON.stringify(network);
	network = JSON.parse(network);
	console.log(network);

	var svg = d3.select("svg"),
	width = +svg.attr("width"),
	height = +svg.attr("height");

    //------- SVG initialization
    var simulation = d3.forceSimulation()
    	.nodes(network.nodes)
	    .force("link", d3.forceLink().id(function(d) { return d.id; }).distance(100))
		.force('charge', d3.forceManyBody()
		  	.strength(-2500)
		  	.theta(0.8)
  	// .distanceMax(150)
    	)
    	.force("center", d3.forceCenter(width / 2, height / 2));

	var g = svg.append("g")
    	.attr("class", "everything");

	//add zoom capabilities 
	var zoom_handler = d3.zoom()
	    .on("zoom", zoom_actions);

    zoom_handler(svg);

    //Zoom functions 
	function zoom_actions(){
	    g.attr("transform", d3.event.transform)
	}

	function run(graph) {
		graph.links.forEach(function(d){
		d.source = d.Source;    
		d.target = d.Target;
		});           

		var link = g.append("g")
            .attr("class", "link")
            .selectAll("line")
            .data(graph.links)
            .enter().append("line")
            	.on('mouseout', fade(1))
            	.attr("stroke-width", 2);

		// Set nodes
		var node = g.append("g")
	        .attr("class", "nodes")
			.selectAll("g")
	        .data(graph.nodes)
			.enter()
		      .append("circle")
		      .attr("class", "circle")
		      .on('mouseover', fade(0.1))
			  .on('mouseout', fade(1))
      	      .on("click", displayed)
		      .call(d3.drag()
		          .on("start", dragstarted)
		          .on("drag", dragged)
		          .on("end", dragended));

        // Set rectangle begind label
        var rect = g.append("g")
        	.attr("class", "rects")
		  	.selectAll("rect")
		  	.data(graph.nodes)
		  	.enter().append("rect")
		  		.attr("class", "rect")
			    .attr("rx", 5) // round shape
			    .attr("ry", 5);

		// Set labels
		var label = g.append("g")
		  .attr("class", "labels")
		  .selectAll("text")
		  .data(graph.nodes)
		  .enter().append("text")
		    .attr("class", "label")
		    .attr("dx", 0)
		    .attr("dy", ".35em")
		    .attr("text-anchor", "middle")
		    .text(function(d) { return d.id; });

	    /* Configuration of simulation */
		simulation
			.nodes(graph.nodes)
			.on("tick", ticked);

		simulation.force("link")
			.links(graph.links);

		/* Set position of network element */
		function ticked() {
			svg.selectAll("text").each(function(d, i) {
		        graph.nodes[i].bb = this.getBBox(); // get bounding box of text field and store it in texts array
		    });

			link
			    .attr("x1", function(d) { return d.source.x; })
			    .attr("y1", function(d) { return d.source.y; })
			    .attr("x2", function(d) { return d.target.x; })
			    .attr("y2", function(d) { return d.target.y; })
	            .style("stroke", "#aaa");

			node
				.attr("cx", function (d) { return d.x; })
                .attr("cy", function (d) { return d.y; })
                .attr("r", 30)
                .attr("fill", function(d){ 
                	if(d.search == true) {
                		return "black";
                	}else{
                		return "grey";
                	}
                });

            rect
            	.attr("x", function(d) { return d.x - d.bb.width/2-2; })
            	.attr("y", function(d) { return d.y - d.bb.height/2; })
			    .attr("width", function(d) { return d.bb.width + 4; })
			    .attr("height", function(d) { return d.bb.height; })
            	.style("fill", "white")
            	.style("opacity", 0.7);


			label
				.attr("x", function(d) { return d.x; })
		        .attr("y", function (d) { return d.y; })
		        .style("font-size", "14px")
		        .style("fill", "#000");
		}

		/* On node mouseover, highlight connected node */
		const linkedByIndex = {};
			graph.links.forEach(d => {
			linkedByIndex[`${d.source.index},${d.target.index}`] = 1;
		});

		function isConnected(a, b) {
			return linkedByIndex[`${a.index},${b.index}`] || linkedByIndex[`${b.index},${a.index}`] || a.index === b.index;
		}

		function fade(opacity) {
		    return d => {
			    node.style('stroke-opacity', function (o) {
			      const thisOpacity = isConnected(d, o) ? 1 : opacity;
			      this.setAttribute('fill-opacity', thisOpacity);
			      return thisOpacity;
			    });

		    	link.style('stroke-opacity', o => (o.source === d || o.target === d ? 1 : opacity));
		    };
		};
	} // End function run

	function dragstarted(d) {
		if (!d3.event.active) simulation.alphaTarget(0.3).restart()
		d.fx = d.x
		d.fy = d.y
		// simulation.fix(d);
	}

	function dragged(d) {
		d.fx = d3.event.x
		d.fy = d3.event.y
	}

	function dragended(d) {
		d.fx = d3.event.x
		d.fy = d3.event.y
		if (!d3.event.active) simulation.alphaTarget(0);
		setTimeout(function(){
			simulation.stop();
		}, 2000);
	}

	function displayed(d) {
		if (d.search != true){
			// Choose between Virus symbol or Virus Uniprot ID
			if (d["Virus Uniprot ID"] != undefined){
				html = '<b>Virus Uniprot ID: </b><a target="_BLANK" href="http://www.uniprot.org/uniprot/' + d["Virus Uniprot ID"] + '" title="UniprotKB Swissprot and Trembl Sequences">' + d["Virus Uniprot ID"] + '</a>';
			}else if (d["Virus_symbol"] != undefined){
				html = '<b>Virus Symbol: </b>' + d["Virus_symbol"];
			}
			// Display Description panel
			document.getElementById("display-info").innerHTML = "" +
				'<h2>Description</h2>' +
				'<div>' + html + '</div>' +
				'<div><b>Alias: </b>' + d.protein_alias_2 + '</div>' +
				'<div><b>Detection method: </b>' + d.method + '</div>' +
				'<div><b>Virus: </b>' + d.virus + '</div>' +
				'<div><b>Species: </b>' + d.species + '</div>' +
				'<div><b>Reference: </b>' + d.author_name + '</div>';
		}else{
			// This is the target so descirption is already displayed
			document.getElementById("display-info").innerHTML = "";
		}
	}

	run(network);

};


$(document).ready( function() {
	$('#display-info').on('show.bs.collapse', function () {
    	$('#display-query').collapse({ 'toggle': false }).collapse('hide');
    	$('#display-config').collapse({ 'toggle': false }).collapse('hide');
	});
});
$(document).ready( function() {
	$('#display-query').on('show.bs.collapse', function () {
    	$('#display-info').collapse({ 'toggle': false }).collapse('hide');
    	$('#display-config').collapse({ 'toggle': false }).collapse('hide');
	});
});
$(document).ready( function() {
	$('#display-config').on('show.bs.collapse', function () {
    	$('#display-query').collapse({ 'toggle': false }).collapse('hide');
    	$('#display-info').collapse({ 'toggle': false }).collapse('hide');
	});
});
