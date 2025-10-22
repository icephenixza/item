<nav class="navbar navbar-expand navbar-light bg-white topbar mb-4 static-top shadow">
    <!-- ปุ่มเปิด/ปิด Sidebar (สำหรับมือถือ) -->
    <button id="sidebarToggleTop" class="btn btn-link d-md-none rounded-circle mr-3">
        <i class="fa fa-bars"></i>
    </button>
    
    <!-- ช่องค้นหาแบบเต็ม -->
    <?php include 'searchfull.php'; ?>
    
    <!-- เมนูด้านบนของ Navbar -->
    <ul class="navbar-nav ml-auto">
        <!-- รายการเมนู - ช่องค้นหาแบบย่อ -->
        <?php include 'searchmini.php'; ?>
        
        <!-- รายการเมนู - การแจ้งเตือน -->
        <?php include 'alert.php'; ?>
        
        <!-- รายการเมนู - ข้อความ -->
        <?php include 'message.php'; ?>
        
        <div class="topbar-divider d-none d-sm-block"></div>
        
        <!-- รายการเมนู - ข้อมูลผู้ใช้ -->
        <?php include 'userinfo.php'; ?>
    </ul>
</nav>