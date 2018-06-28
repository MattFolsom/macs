<!DOCTYPE html>
<?php
// Initialize the session
session_start();
 
// If session variable is not set it will redirect to login page
if(!isset($_SESSION['username']) || empty($_SESSION['username'])){
  header("location: login.php");
  exit;
}
?>
<html>
	<head>
		
		<title>MACS-Logs (<?php echo $_SESSION['username'] ?>)</title>
		<link rel="icon" href="images/MB_Favicon.png">
		<link rel="stylesheet" href="//kendo.cdn.telerik.com/2018.2.516/styles/kendo.common.min.css" />
		<link rel="stylesheet" href="//kendo.cdn.telerik.com/2018.2.516/styles/kendo.materialblack.min.css" />
		<link rel="stylesheet" href="//kendo.cdn.telerik.com/2018.2.516/styles/kendo.default.mobile.min.css" />
		<link rel="stylesheet" href="css/macs.php" type="text/css"/>

		<script src="//kendo.cdn.telerik.com/2018.2.516/js/jquery.min.js"></script>
		<script src="//kendo.cdn.telerik.com/2018.2.516/js/kendo.all.min.js"></script>
		<script src="js/macs.js"></script>
		


		<script>
			$(document).ready(function () {
				setupMenu();
				getLogDataSource();
				makeLogGrid ();
			});
			
			function getLogDataSource (){
				LogDataSource = new kendo.data.DataSource({
					transport: {
						read: {
							url: "json_Log.php",	
							dataType: "jsonp", // "jsonp" is required for cross-domain requests; use "json" for same-domain requests,
							jsonpCallback: 'Log',
							type: "GET"
						},
						create: {
							url: "json_Log.php",	
							dataType: "jsonp", // "jsonp" is required for cross-domain requests; use "json" for same-domain requests,
							jsonpCallback: 'Log',
							type: "PUT"
						}
					}, 
					schema: {
                        model: {
                            fields: {
                                logDateTime: { type: "date" },
                                userName: { type: "string" },
                                machName: { type: "string" },
                                event: { type: "string" },
                                usage: { type: "string" },
                                logonName: { type: "string" }
                            }
                        }
                    },
					requestStart: function() {
						kendo.ui.progress($("#gridLog"), true);
					},
					requestEnd: function() {
						kendo.ui.progress($("#gridLog"), false);
					},
					pageSize: 25
				});	
			}
			
			function makeLogGrid (){
				
				 $("#gridLog").kendoGrid({
					dataSource: LogDataSource,
					sortable: true,
					selectable: "row",
					filterable: {
									mode: "row"
								},
					resizable: true,
					reorderable: true,
					pageable: {
						refresh: true,
						pageSizes: true,
						buttonCount: 5
					},
					columns: [{
						field: "logDateTime",
						title: "Date/Time",
						width: 60,
						format: "{0:ddd MM/dd h:mm tt}",
						filterable: {
                            cell: { template: betweenFilter }
                        }
						}, {
						field: "userName",
						title: "User Name",
						width: 30,
						filterable: {
							cell: {operator: "contains"	}
						}
						}, {
						field: "machName",
						title: "Machine Name",
						width: 30,
						filterable: {
							cell: {operator: "contains"	}
						}
						}, {
						field: "event",
						title: "Event Description",
						width: 30,
						filterable: {
							cell: {operator: "contains"	}
						}
						},  {
						field: "logonName",
						title: "Logon User",
						width: 30,
						filterable: {
							cell: {operator: "contains"	}
						}
						}, {
						field: "usage",
						title: "Duration",
						width: 30
						}]
				});
			}
			
			function betweenFilter(args) {
            var filterCell = args.element.parents(".k-filtercell");

            filterCell.empty();
            filterCell.html('<span  class="filterCell"><span>From:</span><input  class="start-date"/><br/><span>To:</span><input  class="end-date"/></span>');

            $(".start-date", filterCell).kendoDatePicker({
                change: function (e) {
                    var startDate = e.sender.value(),
                        endDate = $("input.end-date", filterCell).data("kendoDatePicker").value(),
                        dataSource = $("#gridLog").data("kendoGrid").dataSource;

                    if (startDate & endDate) {
                        var filter = { logic: "and", filters: [] };
                        filter.filters.push({ field: "logDateTime", operator: "gte", value: startDate });
                        filter.filters.push({ field: "logDateTime", operator: "lte", value: endDate });
                        dataSource.filter(filter);
                    }
                }
            });
            $(".end-date", filterCell).kendoDatePicker({
                change: function (e) {
                    var startDate = $("input.start-date", filterCell).data("kendoDatePicker").value(),
                        endDate = e.sender.value(),
                        dataSource = $("#gridLog").data("kendoGrid").dataSource;

                    if (startDate & endDate) {
                        var filter = { logic: "and", filters: [] };
                        filter.filters.push({ field: "logDateTime", operator: "gte", value: startDate });
                        filter.filters.push({ field: "logDateTime", operator: "lte", value: endDate });
                        dataSource.filter(filter);
                    }
                }
            });

        }
		</script>
		<style>
			span.k-picker-wrap {width:90px; white-space:normal;}
			span.filterCell {
				display:inline-flex; 
				justify-content:center;
			}
		</style>
	</head>
<body>
<div id="master"  class="Content">
	<div id="menu"></div>
    <div id="gridLog" class="AutoRefresh"><h2>MACS Log</h2></div>

</div>
</body>
</html>
