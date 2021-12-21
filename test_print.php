 <!doctype html>
 <html lang='en'>
 <head>
 	<meta charset='utf-8'>
 	<title>test</title>
 	
 </head>
 <body>
<table id="example" class="display" cellspacing="0" width="100%">
        <thead>
            <tr>
                <th>Name</th>
                <th>Position</th>
                <th>Office</th>
                <th>Age</th>
                <th>Start date</th>
                <th>Salary</th>
                <th>test</th>
            </tr>
        </thead>
        <tfoot>
            <tr>
                <th>Name</th>
                <th>Position</th>
                <th>Office</th>
                <th>Age</th>
                <th>Start date</th>
                <th>Salary</th>
                <th>test</th>
            </tr>
        </tfoot>
        <tbody>
            <tr>
                <td>Tiger Nixon</td>
                <td>System Architect</td>
                <td>Edinburgh</td>
                <td>61</td>
                <td>2011/04/25</td>
                <td>$320,800</td>
                <td></td>
            </tr>
            <tr>
                <td>Garrett Winters</td>
                <td>Accountant</td>
                <td>Tokyo</td>
                <td>63</td>
                <td>2011/07/25</td>
                <td>$170,750</td>
                <td>new colonne</td>
            </tr>
            <tr>
                <td>Ashton Cox</td>
                <td>Junior Technical Author</td>
                <td>San Francisco</td>
                <td>66</td>
                <td>2009/01/12</td>
                <td>$86,000</td>
                <td></td>
            </tr>
            <tr>
                <td>Cedric Kelly</td>
                <td>Senior Javascript Developer</td>
                <td>Edinburgh</td>
                <td>22</td>
                <td>2012/03/29</td>
                <td>$433,060</td>
                <td>new colonne</td>
            </tr>
        </tbody>
    </table>
</body>

<script src="https://code.jquery.com/jquery-1.12.3.min.js"></script>
<script src="https://cdn.datatables.net/1.10.12/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/buttons/1.2.1/js/dataTables.buttons.min.js"></script>
<script src="https://cdn.datatables.net/buttons/1.2.1/js/buttons.print.min.js"></script>
<script src="https://cdn.datatables.net/buttons/1.0.1/js/buttons.html5.js"></script>
<script src="https://cdn.datatables.net/buttons/1.0.1/js/buttons.html5.min.js"></script>
<script src='pdfmake-master/build/pdfmake.min.js'></script>
<script src='pdfmake-master/build/vfs_fonts.js'></script>

<script>
$(document).ready(function() {
    $('#example').DataTable( {
        
        "columnDefs": [
            { "width": "10%", "targets": 0 },
            { "width": "20%", "targets": 1 },
            { "width": "1%", "targets": 2 },
            { "width": "10%", "targets": 3 },
            { "width": "1%", "targets": 4 },
            { "width": "1%", "targets": 5 },
            { "width": "12%", "targets": 6,"visible": false  },
        ],
        dom: 'Bfrtip',
        buttons: [ {
            extend: 'pdfHtml5',
            text: 'PDF with image',
            title:'Liste des réserves',
            orientation:'landscape',
            customize: function ( doc ) {
                var cols = [];
                cols[0] = {text: 'Left part', alignment: 'left', margin:[20] };
                cols[1] = {text: 'Right part', alignment: 'right', margin:[0,0,20] };
                var objFooter = {};
                objFooter['columns'] = cols;
                doc['footer']=objFooter;
            }
        }]
    });
});

</script>
</html>
