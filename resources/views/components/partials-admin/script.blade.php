<script src="{{ asset('admin') }}/js/jquery-3.2.1.min.js"></script>
<script src="{{ asset('admin') }}/js/popper.min.js"></script>
<script src="{{ asset('admin') }}/js/bootstrap.min.js"></script>
<script src="{{ asset('admin') }}/js/main.js"></script>

<!-- The javascript plugin to display page loading on top-->
<script src="{{ asset('admin') }}/js/plugins/pace.min.js"></script>

<!-- Page specific javascripts-->
{{-- <script type="text/javascript" src="{{ asset('admin') }}/js/plugins/chart.js"></script> --}}

{{-- <script type="text/javascript">
	var data = {
		labels: ["January", "February", "March", "April", "May"],
		datasets: [{
				label: "My First dataset",
				fillColor: "rgba(220,220,220,0.2)",
				strokeColor: "rgba(220,220,220,1)",
				pointColor: "rgba(220,220,220,1)",
				pointStrokeColor: "#fff",
				pointHighlightFill: "#fff",
				pointHighlightStroke: "rgba(220,220,220,1)",
				data: [65, 59, 80, 81, 56]
			},
			{
				label: "My Second dataset",
				fillColor: "rgba(151,187,205,0.2)",
				strokeColor: "rgba(151,187,205,1)",
				pointColor: "rgba(151,187,205,1)",
				pointStrokeColor: "#fff",
				pointHighlightFill: "#fff",
				pointHighlightStroke: "rgba(151,187,205,1)",
				data: [28, 48, 40, 19, 86]
			}
		]
	};
	var pdata = [{
			value: 300,
			color: "#46BFBD",
			highlight: "#5AD3D1",
			label: "Complete"
		},
		{
			value: 50,
			color: "#F7464A",
			highlight: "#FF5A5E",
			label: "In-Progress"
		}
	]

	var ctxl = $("#lineChartDemo").get(0).getContext("2d");
	var lineChart = new Chart(ctxl).Line(data);

	var ctxp = $("#pieChartDemo").get(0).getContext("2d");
	var pieChart = new Chart(ctxp).Pie(pdata);
</script> --}}

<!-- Google analytics script-->
{{-- <script type="text/javascript">
	if (document.location.hostname == 'pratikborsadiya.in') {
		(function(i, s, o, g, r, a, m) {
			i['GoogleAnalyticsObject'] = r;
			i[r] = i[r] || function() {
				(i[r].q = i[r].q || []).push(arguments)
			}, i[r].l = 1 * new Date();
			a = s.createElement(o),
				m = s.getElementsByTagName(o)[0];
			a.async = 1;
			a.src = g;
			m.parentNode.insertBefore(a, m)
		})(window, document, 'script', '//www.google-analytics.com/analytics.js', 'ga');
		ga('create', 'UA-72504830-1', 'auto');
		ga('send', 'pageview');
	}
</script> --}}

<!-- Ckeditro -->
<script src="https://cdn.ckeditor.com/ckeditor5/41.0.0/classic/ckeditor.js"></script>

<!-- Font awesome -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/js/all.min.js"
	integrity="sha512-b+nQTCdtTBIRIbraqNEwsjB6UvL3UEMkXnhzd8awtCYh0Kcsjl9uEgwVFVbhoj3uu1DO1ZMacNvLoyJJiNfcvg=="
	crossorigin="anonymous" referrerpolicy="no-referrer"></script>
	
<!-- Data table plugin-->
<script type="text/javascript" src="{{ asset('admin') }}/js/plugins/jquery.dataTables.min.js"></script>
<script type="text/javascript" src="{{ asset('admin') }}/js/plugins/dataTables.bootstrap.min.js"></script>
<script type="text/javascript">
	$('#sampleTable').DataTable();
</script>



@stack('scripts')
