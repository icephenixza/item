<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">
    
    <title>ระบบงานผัสดุ</title>

    <?php include 'include.php'; ?>
</head>

<body id="page-top">
    <!-- Page Wrapper -->
    <div id="wrapper">
        <?php include 'app/sidebar.php'; ?>
        
        <!-- Content Wrapper -->
        <div id="content-wrapper" class="d-flex flex-column">
            <!-- Main Content -->
            <div id="content">
                <!-- Topbar -->
                <nav class="navbar navbar-expand navbar-light bg-white topbar mb-4 static-top shadow">
                    <!-- Sidebar Toggle (Topbar) -->
                    <button id="sidebarToggleTop" class="btn btn-link d-md-none rounded-circle mr-3">
                        <i class="fa fa-bars"></i>
                    </button>
                    
                    <!-- Topbar Search -->
                    <?php include 'app/searchfull.php'; ?>
                    
                    <!-- Topbar Navbar -->
                    <ul class="navbar-nav ml-auto">
                        <!-- Nav Item - Search Dropdown (Visible Only XS) -->
                        <?php include 'app/searchmini.php'; ?>
                        
                        <!-- Nav Item - Alerts -->
                        <?php include 'app/alert.php'; ?>
                        
                        <!-- Nav Item - Messages -->
                        <?php include 'app/message.php'; ?>
                        
                        <div class="topbar-divider d-none d-sm-block"></div>
                        
                        <!-- Nav Item - User Information -->
                        <?php include 'app/userinfo.php'; ?>
                    </ul>
                </nav>
                <!-- End of Topbar -->

                <!-- Begin Page Content -->
                <div class="container-fluid">
                    <!-- Page Heading -->
                    <div class="d-sm-flex align-items-center justify-content-between mb-4">
                        <h1 class="h3 mb-0 text-gray-800">รายการวัสดุในคลังสินค้า</h1>
                    </div>
                    <!-- Warehouse Management Content -->
                    <div class="card shadow mb-4">
                        <div class="card-header py-3">
                            <h6 class="m-0 font-weight-bold text-primary">สรุปวัสดุคงเหลือ</h6>
                        </div>
                        <div class="card-body">
                            <!-- Search Box -->
                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <div class="input-group">
                                        <input type="text" class="form-control" id="searchMaterial" placeholder="ค้นหาวัสดุ...">
                                        <div class="input-group-append">
                                            <button class="btn btn-outline-secondary" type="button">
                                                <i class="fas fa-search"></i>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Materials Table -->
                            <div class="table-responsive">
                                <table class="table table-bordered" id="materialsTable" width="100%" cellspacing="0">
                                    <thead>
                                        <tr>
                                            <th>รหัส</th>
                                            <th>ชื่อวัสดุ</th>
                                            <th>คงเหลือ</th>
                                            <th>หน่วย</th>
                                            <th>ราคา/หน่วย</th>
                                            <th>มูลค่า(ราคารวม)</th>
                                            <th>แหล่งที่ซื้อ</th>
                                            <th>จัดการ</th>
                                        </tr>
                                    </thead>
                                    <tbody id="materialsTableBody">
                                        <!-- Data will be loaded via AJAX -->
                                    </tbody>
                                </table>
                            </div>
                            
                            <!-- Loading indicator -->
                            <div id="loadingIndicator" class="text-center" style="display: none;">
                                <div class="spinner-border" role="status">
                                    <span class="sr-only">Loading...</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <script>
                    $(document).ready(function() {
                        // Load materials data on page load
                        loadMaterials();
                        
                        // Search functionality
                        $('#searchMaterial').on('keyup', function() {
                            var searchTerm = $(this).val();
                            loadMaterials(searchTerm);
                        });
                        
                        function loadMaterials(search = '') {
                            $('#loadingIndicator').show();
                            $('#materialsTableBody').html('');
                            
                            $.ajax({
                                url: 'api/get_materials.php',
                                type: 'POST',
                                data: {
                                    search: search
                                },
                                dataType: 'json',
                                success: function(response) {
                                    $('#loadingIndicator').hide();
                                    
                                    if (response.status === 'success') {
                                        var tbody = '';
                                        
                                        if (response.data.length > 0) {
                                            $.each(response.data, function(index, material) {
                                                var remaining = material.total_received - material.total_withdrawn;
                                                var unitPrice = parseFloat(material.unit_price || 0);
                                                var totalValue = remaining * unitPrice;
                                                
                                                tbody += '<tr>';
                                                tbody += '<td>' + material.material_code + '</td>';
                                                tbody += '<td>' + material.material_name + '</td>';
                                                tbody += '<td class="text-center font-weight-bold">' + remaining + '</td>';
                                                tbody += '<td class="text-center">' + material.unit + '</td>';
                                                tbody += '<td class="text-right">' + unitPrice.toLocaleString('th-TH', {minimumFractionDigits: 2}) + '</td>';
                                                tbody += '<td class="text-right font-weight-bold">' + totalValue.toLocaleString('th-TH', {minimumFractionDigits: 2}) + '</td>';
                                                tbody += '<td>' + (material.supplier || '-') + '</td>';
                                                tbody += '<td class="text-center">';
                                                tbody += '<button class="btn btn-sm btn-outline-primary mr-1" onclick="printMaterial(' + material.id + ')" title="พิมพ์"><i class="fas fa-print"></i></button>';
                                                tbody += '<button class="btn btn-sm btn-outline-warning mr-1" onclick="editMaterial(' + material.id + ')" title="แก้ไข"><i class="fas fa-edit"></i></button>';
                                                tbody += '<button class="btn btn-sm btn-outline-danger" onclick="deleteMaterial(' + material.id + ', \'' + material.material_name + '\')" title="ลบ"><i class="fas fa-trash"></i></button>';
                                                tbody += '</td>';
                                                tbody += '</tr>';
                                            });
                                        } else {
                                            tbody = '<tr><td colspan="8" class="text-center">ไม่พบข้อมูลวัสดุ</td></tr>';
                                        }
                                        
                                        $('#materialsTableBody').html(tbody);
                                    } else {
                                        $('#materialsTableBody').html('<tr><td colspan="8" class="text-center text-danger">เกิดข้อผิดพลาดในการโหลดข้อมูล</td></tr>');
                                    }
                                },
                                error: function() {
                                    $('#loadingIndicator').hide();
                                    $('#materialsTableBody').html('<tr><td colspan="8" class="text-center text-danger">เกิดข้อผิดพลาดในการเชื่อมต่อ</td></tr>');
                                }
                            });
                        }
                        
                        // Print function
                        window.printMaterial = function(materialId) {
                            window.open('print_material.php?id=' + materialId, '_blank', 'width=800,height=600');
                        }
                        
                        // Edit function
                        window.editMaterial = function(materialId) {
                            window.location.href = 'edit_material.php?id=' + materialId;
                        }
                        
                        // Delete function
                        window.deleteMaterial = function(materialId, materialName) {
                            if (confirm('คุณต้องการลบวัสดุ "' + materialName + '" หรือไม่?')) {
                                $.ajax({
                                    url: 'api/delete_material.php',
                                    type: 'POST',
                                    data: {
                                        id: materialId
                                    },
                                    dataType: 'json',
                                    success: function(response) {
                                        if (response.status === 'success') {
                                            alert('ลบวัสดุเรียบร้อยแล้ว');
                                            loadMaterials();
                                        } else {
                                            alert('เกิดข้อผิดพลาด: ' + response.message);
                                        }
                                    },
                                    error: function() {
                                        alert('เกิดข้อผิดพลาดในการเชื่อมต่อ');
                                    }
                                });
                            }
                        }
                    });
                    </script>
                    <div class="row">

                    </div>

                </div>
                <!-- /.container-fluid -->
            </div>
            <!-- End of Main Content -->
            
            <!-- Footer -->
            <?php include 'footer.php'; ?>
            <!-- End of Footer -->
        </div>
        <!-- End of Content Wrapper -->
    </div>
    <!-- End of Page Wrapper -->

    <!-- Scroll to Top Button-->
    <a class="scroll-to-top rounded" href="#page-top">
        <i class="fas fa-angle-up"></i>
    </a>

    <!-- Logout Modal-->
    <?php include 'logout.php'; ?>

    <!-- Bootstrap core JavaScript-->
    <script src="vendor/jquery/jquery.min.js"></script>
    <script src="vendor/bootstrap/js/bootstrap.bundle.min.js"></script>

    <!-- Core plugin JavaScript-->
    <script src="vendor/jquery-easing/jquery.easing.min.js"></script>

    <!-- Custom scripts for all pages-->
    <script src="js/sb-admin-2.min.js"></script>

    <!-- Page level plugins -->
    <script src="vendor/chart.js/Chart.min.js"></script>

    <!-- Page level custom scripts -->
    <script src="js/demo/chart-area-demo.js"></script>
    <script src="js/demo/chart-pie-demo.js"></script>

</body>
</html>