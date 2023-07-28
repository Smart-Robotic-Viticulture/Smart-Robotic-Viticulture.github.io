d3.csv('2019_0408_121500_045/cell_lai_gps.csv', function (data) {
  // Variables
  var body = d3.select('body')
	var margin = { top: 50, right: 50, bottom: 50, left: 100 }
	var h = 600 - margin.top - margin.bottom
	var w = 800 - margin.left - margin.right
	var formatPercent = d3.format('.9f')
	// Scales
  // var colorScale = d3.scale.category20()
	// var colorScale = d3.scaleLinear()
 //      .domain(d3.extent(data, d => d.value))
 //      .range([height, 0])

  // var colorScale = d3.scaleLinear().domain([1,10])
  // .range(["white", "blue"])

  //var myColor = d3.scaleSequential().domain([1,10]).interpolator(d3.interpolateViridis);

  var xScale = d3.scale.linear()
    .domain([
    	d3.min([d3.min(data,function (d) { return d.Easting })]),
    	d3.max([d3.max(data,function (d) { return d.Easting })])
    	])
    .range([0,w])
  var yScale = d3.scale.linear()
    .domain([
    	d3.min([d3.min(data,function (d) { return d.Northing })]),
    	d3.max([d3.max(data,function (d) { return d.Northing })])
    	])
    .range([h,0])

  var zScale = d3.scaleSequential()
    .domain([
         d3.min([d3.min(data,function (d) { return d.LAI })]),
         d3.max([d3.max(data,function (d) { return d.LAI })])
         ])
    .interpolator(d3.interpolateViridis)

  // var zScale = d3.scale.linear()
  //    .domain([
  //    	d3.min([d3.min(data,function (d) { return d.LAI })]),
  //    	d3.max([d3.max(data,function (d) { return d.LAI })])
  //    	])
  //    .range(["white", "blue"])


  // var img = d.Images;
	// SVG
	var svg = body.append('svg')
	    .attr('height',h + margin.top + margin.bottom)
	    .attr('width',w + margin.left + margin.right)
	  .append('g')
	    .attr('transform','translate(' + margin.left + ',' + margin.top + ')')
	// X-axis
	var xAxis = d3.svg.axis()
	  .scale(xScale)
	  .tickFormat(formatPercent)
	  .ticks(10)
	  .orient('bottom')
  // Y-axis
	var yAxis = d3.svg.axis()
	  .scale(yScale)
	  .tickFormat(formatPercent)
	  .ticks(10)
	  .orient('left')
  // Circles
  var circles = svg.selectAll('circle')
      .data(data)
      .enter()
    .append('circle')
      .attr('cx',function (d) { return xScale(d.Easting) })
      .attr('cy',function (d) { return yScale(d.Northing) })
      .attr('r','2')
      .attr('stroke','black')
      .attr('stroke-width',0)
      .attr('fill', function (d) {return zScale(d.LAI)})  //function (d,i) { return colorScale(d.maxdd) })
      // var f = 
      // // var imagefile = 'frame0'
      // var file = 'frames111/frame'+ function(d){return d.frame} +'.jpg';
      // var url = function(d){return 'frames111

      // var url = function(d) {return 'frames111/frame'+d.Frame +'.jpg'};
      .on('mouseover', function (d) {
        d3.select(this)
          .transition()
          .duration(1)
          .attr('r',20)
          .attr('stroke-width',0)
          var coords = d3.mouse(this);
          // var f = function(d){return d.frame};
          // // var imagefile = 'frame0'
          // var file = 'frames111/frame'+ f +'.jpg';
          // var url = function(d){return 'frames111/frame'+d.Frame +'.jpg'}
          // console.log(function(d){return './frames111/frame'+d.Frame+'.jpg'});
          $(".hover-change-img img").attr('src', d.Path)
          // .attr('src', 'results.png')
          $(".hover-change-img img").attr('width','212');
          $(".hover-change-img img").attr('height','360');
          $(".hover-change-img img").attr('x', w);
          $(".hover-change-img img").attr('y', 0);     

          // $("document").ready(function(){ 
          //         $(".hover-change-img img").mouseenter(function(){       
          //             $(this).attr('src','results_old.png');
          //             $(this).attr('width','100');
          //             $(this).attr('height','100');           
          //         });     
          //         $(".hover-change-img img").mouseleave(function(){       
          //             $(this).attr('src', 'frames/frame0/frame0.jpg');      
          //             $(this).attr('width','100');
          //             $(this).attr('height','100'); 
          //         }); 
          //     });



      })
      .on('mouseout', function () {
        d3.select(this)
          .transition()
          .duration(1)
          .attr('r',2)
          .attr('stroke-width',0)
          $(".hover-change-img img").attr('src', 'google.png')
          // .attr('src', 'results.png')
          $(".hover-change-img img").attr('width','212')
          $(".hover-change-img img").attr('height','360')    
          $(".hover-change-img img").attr('x', w);
          $(".hover-change-img img").attr('y', 0);   
          // .attr('src', 'results_old.png')
          // .attr('width','100');
          // .attr('height','100');   
      })
    .append('title') // Tooltip
      .text(function (d) { return "LAI: " + formatPercent(d.LAI) +
                           '\nframe: ' + d.Frame +
                           '\npath: ' + d.Path +
                           '\ndirection: ' + d.Direction})//'./frames111/frame'+d.frame +'.jpg'})
    // $("document").ready(function(){ 
    //         $(".hover-change-img img").mouseenter(function(){       
    //             $(this).attr('src','results_old.png');
    //             $(this).attr('width','100');
    //             $(this).attr('height','100');           
    //         });     
    //         $(".hover-change-img img").mouseleave(function(){       
    //             $(this).attr('src', 'frames/frame0/frame0.jpg');      
    //             $(this).attr('width','100');
    //             $(this).attr('height','100'); 
    //         }); 
    //     });
  svg.append("text")
        .attr("x", (w / 2))             
        .attr("y", 0 - (margin.top / 2))
        .attr("text-anchor", "middle")  
        .style("font-size", "16px") 
        .style("text-decoration", "underline")  
        .text("2019_0408_121500_045 (Over exposure)");

  // X-axis
  svg.append('g')
      .attr('class','axis')
      .attr('transform', 'translate(0,' + h + ')')
      .call(xAxis)
    .append('text') // X-axis Label
      .attr('class','label')
      .attr('y',10)
      .attr('x',w)
      .attr('dy','.71em')
      .style('text-anchor','end')
      .text('Easting')

  // Y-axis
  svg.append('g')
      .attr('class', 'axis')
      .call(yAxis)
    .append('text') // y-axis Label
      .attr('class','label')
      .attr('transform','rotate(-90)')
      .attr('x',50)
      .attr('y',50)
      .attr('dy','.71em')
      .style('text-anchor','end')
      .text('Northing')

})

d3.csv('2019_0426_124431_004/cell_lai_gps.csv', function (data) {
  // Variables
  var body = d3.select('body')
	var margin = { top: 50, right: 50, bottom: 50, left: 100 }
	var h = 600 - margin.top - margin.bottom
	var w = 800 - margin.left - margin.right
	var formatPercent = d3.format('.9f')
	// Scales
  // var colorScale = d3.scale.category20()
	// var colorScale = d3.scaleLinear()
 //      .domain(d3.extent(data, d => d.value))
 //      .range([height, 0])

  // var colorScale = d3.scaleLinear().domain([1,10])
  // .range(["white", "blue"])

  //var myColor = d3.scaleSequential().domain([1,10]).interpolator(d3.interpolateViridis);

  var xScale = d3.scale.linear()
    .domain([
    	d3.min([d3.min(data,function (d) { return d.Easting })]),
    	d3.max([d3.max(data,function (d) { return d.Easting })])
    	])
    .range([0,w])
  var yScale = d3.scale.linear()
    .domain([
    	d3.min([d3.min(data,function (d) { return d.Northing })]),
    	d3.max([d3.max(data,function (d) { return d.Northing })])
    	])
    .range([h,0])

  var zScale = d3.scaleSequential()
    .domain([
         d3.min([d3.min(data,function (d) { return d.LAI })]),
         d3.max([d3.max(data,function (d) { return d.LAI })])
         ])
    .interpolator(d3.interpolateViridis)

  // var zScale = d3.scale.linear()
  //    .domain([
  //    	d3.min([d3.min(data,function (d) { return d.LAI })]),
  //    	d3.max([d3.max(data,function (d) { return d.LAI })])
  //    	])
  //    .range(["white", "blue"])


  // var img = d.Images;
	// SVG
	var svg = body.append('svg')
	    .attr('height',h + margin.top + margin.bottom)
	    .attr('width',w + margin.left + margin.right)
	  .append('g')
	    .attr('transform','translate(' + margin.left + ',' + margin.top + ')')
	// X-axis
	var xAxis = d3.svg.axis()
	  .scale(xScale)
	  .tickFormat(formatPercent)
	  .ticks(10)
	  .orient('bottom')
  // Y-axis
	var yAxis = d3.svg.axis()
	  .scale(yScale)
	  .tickFormat(formatPercent)
	  .ticks(10)
	  .orient('left')
  // Circles
  var circles = svg.selectAll('circle')
      .data(data)
      .enter()
    .append('circle')
      .attr('cx',function (d) { return xScale(d.Easting) })
      .attr('cy',function (d) { return yScale(d.Northing) })
      .attr('r','2')
      .attr('stroke','black')
      .attr('stroke-width',0)
      .attr('fill', function (d) {return zScale(d.LAI)})  //function (d,i) { return colorScale(d.maxdd) })
      // var f = 
      // // var imagefile = 'frame0'
      // var file = 'frames111/frame'+ function(d){return d.frame} +'.jpg';
      // var url = function(d){return 'frames111

      // var url = function(d) {return 'frames111/frame'+d.Frame +'.jpg'};
      .on('mouseover', function (d) {
        d3.select(this)
          .transition()
          .duration(1)
          .attr('r',20)
          .attr('stroke-width',0)
          var coords = d3.mouse(this);
          // var f = function(d){return d.frame};
          // // var imagefile = 'frame0'
          // var file = 'frames111/frame'+ f +'.jpg';
          // var url = function(d){return 'frames111/frame'+d.Frame +'.jpg'}
          // console.log(function(d){return './frames111/frame'+d.Frame+'.jpg'});
          $(".hover-change-img img").attr('src', d.Path)
          // .attr('src', 'results.png')
          $(".hover-change-img img").attr('width','212');
          $(".hover-change-img img").attr('height','360');
          $(".hover-change-img img").attr('x', w);
          $(".hover-change-img img").attr('y', 0);     

          // $("document").ready(function(){ 
          //         $(".hover-change-img img").mouseenter(function(){       
          //             $(this).attr('src','results_old.png');
          //             $(this).attr('width','100');
          //             $(this).attr('height','100');           
          //         });     
          //         $(".hover-change-img img").mouseleave(function(){       
          //             $(this).attr('src', 'frames/frame0/frame0.jpg');      
          //             $(this).attr('width','100');
          //             $(this).attr('height','100'); 
          //         }); 
          //     });



      })
      .on('mouseout', function () {
        d3.select(this)
          .transition()
          .duration(1)
          .attr('r',2)
          .attr('stroke-width',0)
          $(".hover-change-img img").attr('src', 'google.png')
          // .attr('src', 'results.png')
          $(".hover-change-img img").attr('width','212')
          $(".hover-change-img img").attr('height','360')    
          $(".hover-change-img img").attr('x', w);
          $(".hover-change-img img").attr('y', 0);   
          // .attr('src', 'results_old.png')
          // .attr('width','100');
          // .attr('height','100');   
      })
    .append('title') // Tooltip
      .text(function (d) { return "LAI: " + formatPercent(d.LAI) +
                           '\nframe: ' + d.Frame +
                           '\npath: ' + d.Path +
                           '\ndirection: ' + d.Direction})//'./frames111/frame'+d.frame +'.jpg'})
    // $("document").ready(function(){ 
    //         $(".hover-change-img img").mouseenter(function(){       
    //             $(this).attr('src','results_old.png');
    //             $(this).attr('width','100');
    //             $(this).attr('height','100');           
    //         });     
    //         $(".hover-change-img img").mouseleave(function(){       
    //             $(this).attr('src', 'frames/frame0/frame0.jpg');      
    //             $(this).attr('width','100');
    //             $(this).attr('height','100'); 
    //         }); 
    //     });

  svg.append("text")
        .attr("x", (w / 2))             
        .attr("y", 0 - (margin.top / 2))
        .attr("text-anchor", "middle")  
        .style("font-size", "16px") 
        .style("text-decoration", "underline")  
        .text("2019_0426_124431_004 (Over exposure)");
  // X-axis
  svg.append('g')
      .attr('class','axis')
      .attr('transform', 'translate(0,' + h + ')')
      .call(xAxis)
    .append('text') // X-axis Label
      .attr('class','label')
      .attr('y',10)
      .attr('x',w)
      .attr('dy','.71em')
      .style('text-anchor','end')
      .text('Easting')
  // Y-axis
  svg.append('g')
      .attr('class', 'axis')
      .call(yAxis)
    .append('text') // y-axis Label
      .attr('class','label')
      .attr('transform','rotate(-90)')
      .attr('x',50)
      .attr('y',50)
      .attr('dy','.71em')
      .style('text-anchor','end')
      .text('Northing')

})

// $("document").ready(function(){ 
//         $(".hover-change-img img").mouseenter(function(){       
//             $(this).attr('src','results_old.png');
//             $(this).attr('width','100');
//             $(this).attr('height','100');           
//         });     
//         $(".hover-change-img img").mouseleave(function(){       
//             $(this).attr('src', 'frames/frame0/frame0.jpg');      
//             $(this).attr('width','100');
//             $(this).attr('height','100'); 
//         }); 
//     });

// $("document").ready(function(){ 
//         $(".hover-change-img img").mouseenter(function(){       
//             $(this).attr('src','results_old.png');      
//         });     
//         $(".hover-change-img img").mouseleave(function(){       
//             $(this).attr('src','results.png');      
//         }); 
//     });


// $(document).ready(function() {
//     $( "#myImg" ).mouseover(function(){
//         $(this).attr("src", "results_old.png");
//         $(this).attr("height", 10)
//         $(this).attr("width", 10)
//     });

//     $( "#myImg" ).mouseout(function(){
//         $(this).attr("src", "results.png");
//         $(this).attr("height", 10)
//         $(this).attr("width", 10)
//     });
// });


// function ShowPicture(id,show, img) {
//   if (show=="1"){
//     document.getElementById(id).style.visibility = "visible"
//     document.getElementById(id).childNodes[1].src = img;
//   }
//   else if (show=="0"){
//     document.getElementById(id).style.visibility = "hidden"
//   }
// }

d3.csv('2019_0426_141700_005/cell_lai_gps.csv', function (data) {
  // Variables
  var body = d3.select('body')
  var margin = { top: 50, right: 50, bottom: 50, left: 100 }
  var h = 600 - margin.top - margin.bottom
  var w = 800 - margin.left - margin.right
  var formatPercent = d3.format('.9f')
  // Scales
  // var colorScale = d3.scale.category20()
  // var colorScale = d3.scaleLinear()
 //      .domain(d3.extent(data, d => d.value))
 //      .range([height, 0])

  // var colorScale = d3.scaleLinear().domain([1,10])
  // .range(["white", "blue"])

  //var myColor = d3.scaleSequential().domain([1,10]).interpolator(d3.interpolateViridis);

  var xScale = d3.scale.linear()
    .domain([
      d3.min([d3.min(data,function (d) { return d.Easting })]),
      d3.max([d3.max(data,function (d) { return d.Easting })])
      ])
    .range([0,w])
  var yScale = d3.scale.linear()
    .domain([
      d3.min([d3.min(data,function (d) { return d.Northing })]),
      d3.max([d3.max(data,function (d) { return d.Northing })])
      ])
    .range([h,0])

  var zScale = d3.scaleSequential()
    .domain([
         d3.min([d3.min(data,function (d) { return d.LAI })]),
         d3.max([d3.max(data,function (d) { return d.LAI })])
         ])
    .interpolator(d3.interpolateViridis)

  // var zScale = d3.scale.linear()
  //    .domain([
  //      d3.min([d3.min(data,function (d) { return d.LAI })]),
  //      d3.max([d3.max(data,function (d) { return d.LAI })])
  //      ])
  //    .range(["white", "blue"])


  // var img = d.Images;
  // SVG
  var svg = body.append('svg')
      .attr('height',h + margin.top + margin.bottom)
      .attr('width',w + margin.left + margin.right)
    .append('g')
      .attr('transform','translate(' + margin.left + ',' + margin.top + ')')
  // X-axis
  var xAxis = d3.svg.axis()
    .scale(xScale)
    .tickFormat(formatPercent)
    .ticks(10)
    .orient('bottom')
  // Y-axis
  var yAxis = d3.svg.axis()
    .scale(yScale)
    .tickFormat(formatPercent)
    .ticks(10)
    .orient('left')
  // Circles
  var circles = svg.selectAll('circle')
      .data(data)
      .enter()
    .append('circle')
      .attr('cx',function (d) { return xScale(d.Easting) })
      .attr('cy',function (d) { return yScale(d.Northing) })
      .attr('r','2')
      .attr('stroke','black')
      .attr('stroke-width',0)
      .attr('fill', function (d) {return zScale(d.LAI)})  //function (d,i) { return colorScale(d.maxdd) })
      // var f = 
      // // var imagefile = 'frame0'
      // var file = 'frames111/frame'+ function(d){return d.frame} +'.jpg';
      // var url = function(d){return 'frames111

      // var url = function(d) {return 'frames111/frame'+d.Frame +'.jpg'};
      .on('mouseover', function (d) {
        d3.select(this)
          .transition()
          .duration(1)
          .attr('r',20)
          .attr('stroke-width',0)
          var coords = d3.mouse(this);
          // var f = function(d){return d.frame};
          // // var imagefile = 'frame0'
          // var file = 'frames111/frame'+ f +'.jpg';
          // var url = function(d){return 'frames111/frame'+d.Frame +'.jpg'}
          // console.log(function(d){return './frames111/frame'+d.Frame+'.jpg'});
          $(".hover-change-img img").attr('src', d.Path)
          // .attr('src', 'results.png')
          $(".hover-change-img img").attr('width','212');
          $(".hover-change-img img").attr('height','360');
          $(".hover-change-img img").attr('x', w);
          $(".hover-change-img img").attr('y', 0);     

          // $("document").ready(function(){ 
          //         $(".hover-change-img img").mouseenter(function(){       
          //             $(this).attr('src','results_old.png');
          //             $(this).attr('width','100');
          //             $(this).attr('height','100');           
          //         });     
          //         $(".hover-change-img img").mouseleave(function(){       
          //             $(this).attr('src', 'frames/frame0/frame0.jpg');      
          //             $(this).attr('width','100');
          //             $(this).attr('height','100'); 
          //         }); 
          //     });



      })
      .on('mouseout', function () {
        d3.select(this)
          .transition()
          .duration(1)
          .attr('r',2)
          .attr('stroke-width',0)
          $(".hover-change-img img").attr('src', 'google.png')
          // .attr('src', 'results.png')
          $(".hover-change-img img").attr('width','212')
          $(".hover-change-img img").attr('height','360')    
          $(".hover-change-img img").attr('x', w);
          $(".hover-change-img img").attr('y', 0);   
          // .attr('src', 'results_old.png')
          // .attr('width','100');
          // .attr('height','100');   
      })
    .append('title') // Tooltip
      .text(function (d) { return "LAI: " + formatPercent(d.LAI) +
                           '\nframe: ' + d.Frame +
                           '\npath: ' + d.Path +
                           '\ndirection: ' + d.Direction})//'./frames111/frame'+d.frame +'.jpg'})
    // $("document").ready(function(){ 
    //         $(".hover-change-img img").mouseenter(function(){       
    //             $(this).attr('src','results_old.png');
    //             $(this).attr('width','100');
    //             $(this).attr('height','100');           
    //         });     
    //         $(".hover-change-img img").mouseleave(function(){       
    //             $(this).attr('src', 'frames/frame0/frame0.jpg');      
    //             $(this).attr('width','100');
    //             $(this).attr('height','100'); 
    //         }); 
    //     });

  svg.append("text")
        .attr("x", (w / 2))             
        .attr("y", 0 - (margin.top / 2))
        .attr("text-anchor", "middle")  
        .style("font-size", "16px") 
        .style("text-decoration", "underline")  
        .text("2019_0426_141700_005 (Cloudy)");
  // X-axis
  svg.append('g')
      .attr('class','axis')
      .attr('transform', 'translate(0,' + h + ')')
      .call(xAxis)
    .append('text') // X-axis Label
      .attr('class','label')
      .attr('y',10)
      .attr('x',w)
      .attr('dy','.71em')
      .style('text-anchor','end')
      .text('Easting')
  // Y-axis
  svg.append('g')
      .attr('class', 'axis')
      .call(yAxis)
    .append('text') // y-axis Label
      .attr('class','label')
      .attr('transform','rotate(-90)')
      .attr('x',50)
      .attr('y',50)
      .attr('dy','.71em')
      .style('text-anchor','end')
      .text('Northing')

})



d3.csv('2019_0426_152911_006/cell_lai_gps.csv', function (data) {
  // Variables
  var body = d3.select('body')
  var margin = { top: 50, right: 50, bottom: 50, left: 100 }
  var h = 600 - margin.top - margin.bottom
  var w = 800 - margin.left - margin.right
  var formatPercent = d3.format('.9f')
  // Scales
  // var colorScale = d3.scale.category20()
  // var colorScale = d3.scaleLinear()
 //      .domain(d3.extent(data, d => d.value))
 //      .range([height, 0])

  // var colorScale = d3.scaleLinear().domain([1,10])
  // .range(["white", "blue"])

  //var myColor = d3.scaleSequential().domain([1,10]).interpolator(d3.interpolateViridis);

  var xScale = d3.scale.linear()
    .domain([
      d3.min([d3.min(data,function (d) { return d.Easting })]),
      d3.max([d3.max(data,function (d) { return d.Easting })])
      ])
    .range([0,w])
  var yScale = d3.scale.linear()
    .domain([
      d3.min([d3.min(data,function (d) { return d.Northing })]),
      d3.max([d3.max(data,function (d) { return d.Northing })])
      ])
    .range([h,0])

  var zScale = d3.scaleSequential()
    .domain([
         d3.min([d3.min(data,function (d) { return d.LAI })]),
         d3.max([d3.max(data,function (d) { return d.LAI })])
         ])
    .interpolator(d3.interpolateViridis)

  // var zScale = d3.scale.linear()
  //    .domain([
  //      d3.min([d3.min(data,function (d) { return d.LAI })]),
  //      d3.max([d3.max(data,function (d) { return d.LAI })])
  //      ])
  //    .range(["white", "blue"])


  // var img = d.Images;
  // SVG
  var svg = body.append('svg')
      .attr('height',h + margin.top + margin.bottom)
      .attr('width',w + margin.left + margin.right)
    .append('g')
      .attr('transform','translate(' + margin.left + ',' + margin.top + ')')
  // X-axis
  var xAxis = d3.svg.axis()
    .scale(xScale)
    .tickFormat(formatPercent)
    .ticks(10)
    .orient('bottom')
  // Y-axis
  var yAxis = d3.svg.axis()
    .scale(yScale)
    .tickFormat(formatPercent)
    .ticks(10)
    .orient('left')
  // Circles
  var circles = svg.selectAll('circle')
      .data(data)
      .enter()
    .append('circle')
      .attr('cx',function (d) { return xScale(d.Easting) })
      .attr('cy',function (d) { return yScale(d.Northing) })
      .attr('r','2')
      .attr('stroke','black')
      .attr('stroke-width',0)
      .attr('fill', function (d) {return zScale(d.LAI)})  //function (d,i) { return colorScale(d.maxdd) })
      // var f = 
      // // var imagefile = 'frame0'
      // var file = 'frames111/frame'+ function(d){return d.frame} +'.jpg';
      // var url = function(d){return 'frames111

      // var url = function(d) {return 'frames111/frame'+d.Frame +'.jpg'};
      .on('mouseover', function (d) {
        d3.select(this)
          .transition()
          .duration(1)
          .attr('r',20)
          .attr('stroke-width',0)
          var coords = d3.mouse(this);
          // var f = function(d){return d.frame};
          // // var imagefile = 'frame0'
          // var file = 'frames111/frame'+ f +'.jpg';
          // var url = function(d){return 'frames111/frame'+d.Frame +'.jpg'}
          // console.log(function(d){return './frames111/frame'+d.Frame+'.jpg'});
          $(".hover-change-img img").attr('src', d.Path)
          // .attr('src', 'results.png')
          $(".hover-change-img img").attr('width','212');
          $(".hover-change-img img").attr('height','360');
          $(".hover-change-img img").attr('x', w);
          $(".hover-change-img img").attr('y', 0);     

          // $("document").ready(function(){ 
          //         $(".hover-change-img img").mouseenter(function(){       
          //             $(this).attr('src','results_old.png');
          //             $(this).attr('width','100');
          //             $(this).attr('height','100');           
          //         });     
          //         $(".hover-change-img img").mouseleave(function(){       
          //             $(this).attr('src', 'frames/frame0/frame0.jpg');      
          //             $(this).attr('width','100');
          //             $(this).attr('height','100'); 
          //         }); 
          //     });



      })
      .on('mouseout', function () {
        d3.select(this)
          .transition()
          .duration(1)
          .attr('r',2)
          .attr('stroke-width',0)
          $(".hover-change-img img").attr('src', 'google.png')
          // .attr('src', 'results.png')
          $(".hover-change-img img").attr('width','212')
          $(".hover-change-img img").attr('height','360')    
          $(".hover-change-img img").attr('x', w);
          $(".hover-change-img img").attr('y', 0);   
          // .attr('src', 'results_old.png')
          // .attr('width','100');
          // .attr('height','100');   
      })
    .append('title') // Tooltip
      .text(function (d) { return "LAI: " + formatPercent(d.LAI) +
                           '\nframe: ' + d.Frame +
                           '\npath: ' + d.Path +
                           '\ndirection: ' + d.Direction})//'./frames111/frame'+d.frame +'.jpg'})
    // $("document").ready(function(){ 
    //         $(".hover-change-img img").mouseenter(function(){       
    //             $(this).attr('src','results_old.png');
    //             $(this).attr('width','100');
    //             $(this).attr('height','100');           
    //         });     
    //         $(".hover-change-img img").mouseleave(function(){       
    //             $(this).attr('src', 'frames/frame0/frame0.jpg');      
    //             $(this).attr('width','100');
    //             $(this).attr('height','100'); 
    //         }); 
    //     });
  svg.append("text")
        .attr("x", (w / 2))             
        .attr("y", 0 - (margin.top / 2))
        .attr("text-anchor", "middle")  
        .style("font-size", "16px") 
        .style("text-decoration", "underline")  
        .text("2019_0426_152911_006 (Cloudy)");

  // X-axis
  svg.append('g')
      .attr('class','axis')
      .attr('transform', 'translate(0,' + h + ')')
      .call(xAxis)
    .append('text') // X-axis Label
      .attr('class','label')
      .attr('y',10)
      .attr('x',w)
      .attr('dy','.71em')
      .style('text-anchor','end')
      .text('Easting')
  // Y-axis
  svg.append('g')
      .attr('class', 'axis')
      .call(yAxis)
    .append('text') // y-axis Label
      .attr('class','label')
      .attr('transform','rotate(-90)')
      .attr('x',50)
      .attr('y',50)
      .attr('dy','.71em')
      .style('text-anchor','end')
      .text('Northing')

})