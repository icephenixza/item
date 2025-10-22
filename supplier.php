<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">
    
    <title>ระบบงานผัสดุ - จัดการข้อมูลผู้จำหน่าย</title>

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
                <?php include 'app/topbar.php'; ?>
                <!-- End of Topbar -->

                <!-- Begin Page Content -->
                <div class="container-fluid">
                    <!-- Page Heading -->
                    <div class="d-sm-flex align-items-center justify-content-between mb-4">
                        <h1 class="h3 mb-0 text-gray-800">จัดการข้อมูลผู้จำหน่าย</h1>
                        <button class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm" onclick="openAddModal()">
                            <i class="fas fa-plus fa-sm text-white-50"></i> เพิ่มผู้จำหน่าย
                        </button>
                    </div>

                    <!-- Alert Messages -->
                    <div id="alertMessage" class="alert" style="display: none;" role="alert"></div>

                    <!-- Search and Filter Section -->
                    <div class="card shadow mb-4">
                        <div class="card-header py-3">
                            <h6 class="m-0 font-weight-bold text-primary">ค้นหาและกรองข้อมูล</h6>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="searchSupplier">ค้นหา:</label>
                                        <input type="text" id="searchSupplier" class="form-control" 
                                               placeholder="ค้นหาด้วยชื่อ, รหัส, หรือเบอร์โทร...">
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="statusFilter">สถานะ:</label>
                                        <select id="statusFilter" class="form-control">
                                            <option value="">ทั้งหมด</option>
                                            <option value="active">ใช้งาน</option>
                                            <option value="inactive">ไม่ใช้งาน</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="recordsPerPage">แสดงผล:</label>
                                        <select id="recordsPerPage" class="form-control">
                                            <option value="10">10 รายการ</option>
                                            <option value="25">25 รายการ</option>
                                            <option value="50">50 รายการ</option>
                                            <option value="100">100 รายการ</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Suppliers Table -->
                    <div class="card shadow mb-4">
                        <div class="card-header py-3">
                            <h6 class="m-0 font-weight-bold text-primary">รายการผู้จำหน่าย</h6>
                        </div>
                        <div class="card-body">
                            <!-- Loading Indicator -->
                            <div id="loadingIndicator" class="text-center py-4" style="display: none;">
                                <div class="spinner-border text-primary" role="status">
                                    <span class="sr-only">กำลังโหลด...</span>
                                </div>
                                <p class="mt-2">กำลังโหลดข้อมูล...</p>
                            </div>

                            <!-- Table -->
                            <div class="table-responsive">
                                <table class="table table-bordered" id="suppliersTable" width="100%" cellspacing="0">
                                    <thead>
                                        <tr>
                                            <th width="5%">#</th>
                                            <th width="10%">รหัส</th>
                                            <th width="20%">ชื่อผู้จำหน่าย</th>
                                            <th width="15%">ติดต่อ</th>
                                            <th width="15%">เบอร์โทร</th>
                                            <th width="20%">ที่อยู่</th>
                                            <th width="8%">สถานะ</th>
                                            <th width="12%">จัดการ</th>
                                        </tr>
                                    </thead>
                                    <tbody id="suppliersTableBody">
                                        <!-- Data will be loaded via AJAX -->
                                    </tbody>
                                </table>
                            </div>

                            <!-- Pagination -->
                            <div class="row mt-3">
                                <div class="col-md-6">
                                    <div id="tableInfo" class="dataTables_info"></div>
                                </div>
                                <div class="col-md-6">
                                    <nav aria-label="Table pagination">
                                        <ul id="pagination" class="pagination justify-content-end mb-0">
                                            <!-- Pagination buttons will be generated here -->
                                        </ul>
                                    </nav>
                                </div>
                            </div>
                        </div>
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

    <!-- Add/Edit Supplier Modal -->
    <div class="modal fade" id="supplierModal" tabindex="-1" role="dialog" aria-labelledby="supplierModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="supplierModalLabel">เพิ่มผู้จำหน่าย</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form id="supplierForm">
                    <div class="modal-body">
                        <input type="hidden" id="supplierId" name="id">
                        
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="supplierCode">รหัสผู้จำหน่าย <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" id="supplierCode" name="code" required>
                                    <small class="form-text text-muted">รหัสต้องไม่ซ้ำกัน</small>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="supplierStatus">สถานะ</label>
                                    <select class="form-control" id="supplierStatus" name="status">
                                        <option value="active">ใช้งาน</option>
                                        <option value="inactive">ไม่ใช้งาน</option>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="supplierName">ชื่อผู้จำหน่าย <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="supplierName" name="name" required>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="contactPerson">ชื่อผู้ติดต่อ</label>
                                    <input type="text" class="form-control" id="contactPerson" name="contact_person">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="supplierPhone">เบอร์โทรศัพท์</label>
                                    <input type="text" class="form-control" id="supplierPhone" name="phone">
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="supplierEmail">อีเมล</label>
                                    <input type="email" class="form-control" id="supplierEmail" name="email">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="supplierWebsite">เว็บไซต์</label>
                                    <input type="url" class="form-control" id="supplierWebsite" name="website">
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="supplierAddress">ที่อยู่</label>
                            <textarea class="form-control" id="supplierAddress" name="address" rows="3"></textarea>
                        </div>

                        <div class="form-group">
                            <label for="supplierNotes">หมายเหตุ</label>
                            <textarea class="form-control" id="supplierNotes" name="notes" rows="2"></textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">ยกเลิก</button>
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save"></i> บันทึก
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Delete Confirmation Modal -->
    <div class="modal fade" id="deleteModal" tabindex="-1" role="dialog" aria-labelledby="deleteModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="deleteModalLabel">ยืนยันการลบ</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <p>คุณแน่ใจหรือไม่ที่จะลบผู้จำหน่าย "<span id="deleteSupplierName"></span>"?</p>
                    <p class="text-danger"><small>การลบจะไม่สามารถกู้คืนได้</small></p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">ยกเลิก</button>
                    <button type="button" class="btn btn-danger" onclick="confirmDelete()">
                        <i class="fas fa-trash"></i> ลบ
                    </button>
                </div>
            </div>
        </div>
    </div>

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

    <script>
        let currentPage = 1;
        let deleteId = null;

        $(document).ready(function() {
            loadSuppliers();

            // Search functionality
            $('#searchSupplier').on('keyup', function() {
                currentPage = 1;
                loadSuppliers();
            });

            // Status filter
            $('#statusFilter').on('change', function() {
                currentPage = 1;
                loadSuppliers();
            });

            // Records per page
            $('#recordsPerPage').on('change', function() {
                currentPage = 1;
                loadSuppliers();
            });

            // Form submission
            $('#supplierForm').on('submit', function(e) {
                e.preventDefault();
                saveSupplier();
            });
        });

        function loadSuppliers() {
            const search = $('#searchSupplier').val();
            const status = $('#statusFilter').val();
            const limit = $('#recordsPerPage').val();

            $('#loadingIndicator').show();
            $('#suppliersTableBody').html('');

            $.ajax({
                url: 'api/suppliers.php',
                type: 'POST',
                data: {
                    action: 'list',
                    search: search,
                    status: status,
                    page: currentPage,
                    limit: limit
                },
                dataType: 'json',
                success: function(response) {
                    $('#loadingIndicator').hide();
                    
                    if (response.status === 'success') {
                        displaySuppliers(response.data);
                        generatePagination(response.pagination);
                        updateTableInfo(response.pagination);
                    } else {
                        showAlert('danger', response.message || 'เกิดข้อผิดพลาดในการโหลดข้อมูล');
                        $('#suppliersTableBody').html('<tr><td colspan="8" class="text-center text-danger">เกิดข้อผิดพลาดในการโหลดข้อมูล</td></tr>');
                    }
                },
                error: function() {
                    $('#loadingIndicator').hide();
                    showAlert('danger', 'เกิดข้อผิดพลาดในการเชื่อมต่อ');
                    $('#suppliersTableBody').html('<tr><td colspan="8" class="text-center text-danger">เกิดข้อผิดพลาดในการเชื่อมต่อ</td></tr>');
                }
            });
        }

        function displaySuppliers(suppliers) {
            let tbody = '';
            
            if (suppliers.length > 0) {
                suppliers.forEach(function(supplier, index) {
                    const startIndex = (currentPage - 1) * parseInt($('#recordsPerPage').val());
                    const rowNumber = startIndex + index + 1;
                    
                    tbody += `
                        <tr>
                            <td>${rowNumber}</td>
                            <td>${supplier.code}</td>
                            <td>${supplier.name}</td>
                            <td>${supplier.contact_person || '-'}</td>
                            <td>${supplier.phone || '-'}</td>
                            <td>${supplier.address ? supplier.address.substring(0, 50) + (supplier.address.length > 50 ? '...' : '') : '-'}</td>
                            <td>
                                <span class="badge badge-${supplier.status === 'active' ? 'success' : 'secondary'}">
                                    ${supplier.status === 'active' ? 'ใช้งาน' : 'ไม่ใช้งาน'}
                                </span>
                            </td>
                            <td>
                                <div class="btn-group" role="group">
                                    <button class="btn btn-sm btn-outline-primary" onclick="editSupplier(${supplier.id})" title="แก้ไข">
                                        <i class="fas fa-edit"></i>
                                    </button>
                                    <button class="btn btn-sm btn-outline-danger" onclick="deleteSupplier(${supplier.id}, '${supplier.name}')" title="ลบ">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    `;
                });
            } else {
                tbody = '<tr><td colspan="8" class="text-center text-muted">ไม่พบข้อมูลผู้จำหน่าย</td></tr>';
            }
            
            $('#suppliersTableBody').html(tbody);
        }

        function generatePagination(pagination) {
            let paginationHtml = '';
            
            if (pagination.total_pages > 1) {
                // Previous button
                if (pagination.current_page > 1) {
                    paginationHtml += `<li class="page-item"><a class="page-link" href="#" onclick="changePage(${pagination.current_page - 1})">ก่อนหน้า</a></li>`;
                }
                
                // Page numbers
                for (let i = 1; i <= pagination.total_pages; i++) {
                    if (i === pagination.current_page) {
                        paginationHtml += `<li class="page-item active"><span class="page-link">${i}</span></li>`;
                    } else {
                        paginationHtml += `<li class="page-item"><a class="page-link" href="#" onclick="changePage(${i})">${i}</a></li>`;
                    }
                }
                
                // Next button
                if (pagination.current_page < pagination.total_pages) {
                    paginationHtml += `<li class="page-item"><a class="page-link" href="#" onclick="changePage(${pagination.current_page + 1})">ถัดไป</a></li>`;
                }
            }
            
            $('#pagination').html(paginationHtml);
        }

        function updateTableInfo(pagination) {
            const start = (pagination.current_page - 1) * pagination.limit + 1;
            const end = Math.min(start + pagination.limit - 1, pagination.total_records);
            
            $('#tableInfo').html(`แสดง ${start} ถึง ${end} จาก ${pagination.total_records} รายการ`);
        }

        function changePage(page) {
            currentPage = page;
            loadSuppliers();
        }

        function openAddModal() {
            $('#supplierModalLabel').text('เพิ่มผู้จำหน่าย');
            $('#supplierForm')[0].reset();
            $('#supplierId').val('');
            $('#supplierModal').modal('show');
        }

        function editSupplier(id) {
            $.ajax({
                url: 'api/suppliers.php',
                type: 'POST',
                data: {
                    action: 'get',
                    id: id
                },
                dataType: 'json',
                success: function(response) {
                    if (response.status === 'success') {
                        const supplier = response.data;
                        
                        $('#supplierModalLabel').text('แก้ไขผู้จำหน่าย');
                        $('#supplierId').val(supplier.id);
                        $('#supplierCode').val(supplier.code);
                        $('#supplierName').val(supplier.name);
                        $('#contactPerson').val(supplier.contact_person);
                        $('#supplierPhone').val(supplier.phone);
                        $('#supplierEmail').val(supplier.email);
                        $('#supplierWebsite').val(supplier.website);
                        $('#supplierAddress').val(supplier.address);
                        $('#supplierNotes').val(supplier.notes);
                        $('#supplierStatus').val(supplier.status);
                        
                        $('#supplierModal').modal('show');
                    } else {
                        showAlert('danger', response.message || 'เกิดข้อผิดพลาดในการโหลดข้อมูล');
                    }
                },
                error: function() {
                    showAlert('danger', 'เกิดข้อผิดพลาดในการเชื่อมต่อ');
                }
            });
        }

        function saveSupplier() {
            const formData = new FormData($('#supplierForm')[0]);
            const action = $('#supplierId').val() ? 'update' : 'create';
            formData.append('action', action);

            $.ajax({
                url: 'api/suppliers.php',
                type: 'POST',
                data: formData,
                processData: false,
                contentType: false,
                dataType: 'json',
                success: function(response) {
                    if (response.status === 'success') {
                        $('#supplierModal').modal('hide');
                        showAlert('success', response.message);
                        loadSuppliers();
                    } else {
                        showAlert('danger', response.message || 'เกิดข้อผิดพลาดในการบันทึกข้อมูล');
                    }
                },
                error: function() {
                    showAlert('danger', 'เกิดข้อผิดพลาดในการเชื่อมต่อ');
                }
            });
        }

        function deleteSupplier(id, name) {
            deleteId = id;
            $('#deleteSupplierName').text(name);
            $('#deleteModal').modal('show');
        }

        function confirmDelete() {
            if (deleteId) {
                $.ajax({
                    url: 'api/suppliers.php',
                    type: 'POST',
                    data: {
                        action: 'delete',
                        id: deleteId
                    },
                    dataType: 'json',
                    success: function(response) {
                        $('#deleteModal').modal('hide');
                        
                        if (response.status === 'success') {
                            showAlert('success', response.message);
                            loadSuppliers();
                        } else {
                            showAlert('danger', response.message || 'เกิดข้อผิดพลาดในการลบข้อมูล');
                        }
                        
                        deleteId = null;
                    },
                    error: function() {
                        $('#deleteModal').modal('hide');
                        showAlert('danger', 'เกิดข้อผิดพลาดในการเชื่อมต่อ');
                        deleteId = null;
                    }
                });
            }
        }

        function showAlert(type, message) {
            const alertClass = type === 'success' ? 'alert-success' : 'alert-danger';
            $('#alertMessage')
                .removeClass('alert-success alert-danger')
                .addClass(alertClass)
                .text(message)
                .show();
            
            setTimeout(function() {
                $('#alertMessage').fadeOut();
            }, 5000);
        }
    </script>
</body>
</html>
