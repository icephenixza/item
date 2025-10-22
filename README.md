# ระบบจัดการวัสดุสำนักงาน (Office Material Management System)

ระบบจัดการวัสดุสำนักงานสำหรับหน่วยงานราชการ พัฒนาด้วย PHP และ MySQL

## ✨ ฟีเจอร์หลัก

- 📊 **Dashboard** - ภาพรวมสถิติการใช้วัสดุ
- 📦 **รับวัสดุเข้าคลัง** - บันทึกการรับวัสดุจากผู้จำหน่าย
- 📤 **เบิกวัสดุออก** - ระบบเบิกวัสดุสำหรับแผนกต่างๆ
- 🏪 **จัดการคลังวัสดุ** - ติดตามสต็อกวัสดุคงเหลือ
- 📈 **รายงาน** - รายงานสรุปการใช้วัสดุแยกตามหมวดหมู่และแผนก
- 👥 **จัดการผู้ใช้** - ระบบสิทธิ์การใช้งาน (Admin/Staff/User)

## 🛠️ เทคโนโลยีที่ใช้

- **Frontend**: Bootstrap 4 (SB Admin 2 Theme), jQuery, Chart.js
- **Backend**: PHP 7.4+
- **Database**: MySQL 5.7+
- **Server**: Apache (XAMPP)

## 📋 ความต้องการของระบบ

- PHP 7.4 หรือสูงกว่า
- MySQL 5.7 หรือสูงกว่า
- Apache Server
- XAMPP (แนะนำ)

## 🚀 การติดตั้ง

### 1. Clone โปรเจ็ค
```bash
git clone https://github.com/[your-username]/item2.git
cd item2
```

### 2. ตั้งค่า XAMPP
- เปิด XAMPP Control Panel
- Start Apache และ MySQL

### 3. สร้างฐานข้อมูล
- เปิด http://localhost/phpmyadmin
- สร้างฐานข้อมูลใหม่ชื่อ: `office_material_system`
- Import ไฟล์ `database_design.sql`

### 4. ปรับแต่งการเชื่อมต่อฐานข้อมูล
แก้ไขไฟล์ `config/database.php`:
```php
private $host = 'localhost';
private $db_name = 'office_material_system';
private $username = 'root';
private $password = '';
```

### 5. เข้าใช้งานระบบ
```
http://localhost/item2/
```

## 👤 ข้อมูล Login เริ่มต้น

| Username | Password | Role |
|----------|----------|------|
| admin | admin123 | ผู้ดูแลระบบ |

## 📁 โครงสร้างโปรเจ็ค

```
item2/
├── config/             # ไฟล์การตั้งค่า
│   └── database.php    # การเชื่อมต่อฐานข้อมูล
├── classes/            # PHP Classes
│   └── Material.php    # Class จัดการวัสดุ
├── css/               # CSS Files
├── js/                # JavaScript Files
├── vendor/            # Bootstrap & Libraries
├── database_design.sql # โครงสร้างฐานข้อมูล
├── index.php          # หน้าหลัก Dashboard
├── input.php          # หน้ารับวัสดุ
└── sidebar.php        # เมนูด้านข้าง
```

## 🗄️ โครงสร้างฐานข้อมูล

- **categories** - หมวดหมู่วัสดุ
- **suppliers** - ผู้จำหน่าย/แหล่งที่มา
- **materials** - ข้อมูลวัสดุ
- **users** - ผู้ใช้งานระบบ
- **material_receipts** - การรับวัสดุเข้า
- **material_withdrawals** - การเบิกวัสดุออก
- **material_movements** - ประวัติการเคลื่อนไหวสต็อก

## 📸 ภาพหน้าจอ

### Dashboard
![Dashboard](https://via.placeholder.com/800x400?text=Dashboard+Screenshot)

### ฟอร์มรับวัสดุ
![Input Form](https://via.placeholder.com/800x400?text=Material+Input+Form)

## 🤝 การพัฒนา

ถ้าต้องการร่วมพัฒนาโปรเจ็คนี้:

1. Fork โปรเจ็ค
2. สร้าง feature branch (`git checkout -b feature/AmazingFeature`)
3. Commit การเปลี่ยนแปลง (`git commit -m 'Add some AmazingFeature'`)
4. Push ไปยัง branch (`git push origin feature/AmazingFeature`)
5. เปิด Pull Request

## 📝 License

โปรเจ็คนี้ใช้ MIT License - ดู [LICENSE](LICENSE) สำหรับรายละเอียด

## 📞 ติดต่อ

- Email: your.email@example.com
- GitHub: [@yourusername](https://github.com/yourusername)

---

**🏛️ พัฒนาสำหรับหน่วยงานราชการไทย**

**[Launch Live Preview](https://startbootstrap.github.io/startbootstrap-sb-admin-2/)**

## Status

[![GitHub license](https://img.shields.io/badge/license-MIT-blue.svg)](https://raw.githubusercontent.com/StartBootstrap/startbootstrap-sb-admin-2/master/LICENSE)
[![npm version](https://img.shields.io/npm/v/startbootstrap-sb-admin-2.svg)](https://www.npmjs.com/package/startbootstrap-sb-admin-2)
[![Build Status](https://travis-ci.org/StartBootstrap/startbootstrap-sb-admin-2.svg?branch=master)](https://travis-ci.org/StartBootstrap/startbootstrap-sb-admin-2)
[![dependencies Status](https://david-dm.org/StartBootstrap/startbootstrap-sb-admin-2/status.svg)](https://david-dm.org/StartBootstrap/startbootstrap-sb-admin-2)
[![devDependencies Status](https://david-dm.org/StartBootstrap/startbootstrap-sb-admin-2/dev-status.svg)](https://david-dm.org/StartBootstrap/startbootstrap-sb-admin-2?type=dev)

## Download and Installation

To begin using this template, choose one of the following options to get started:

* [Download the latest release on Start Bootstrap](https://startbootstrap.com/theme/sb-admin-2/)
* Install via npm: `npm i startbootstrap-sb-admin-2`
* Clone the repo: `git clone https://github.com/StartBootstrap/startbootstrap-sb-admin-2.git`
* [Fork, Clone, or Download on GitHub](https://github.com/StartBootstrap/startbootstrap-sb-admin-2)

## Usage

After installation, run `npm install` and then run `npm start` which will open up a preview of the template in your default browser, watch for changes to core template files, and live reload the browser when changes are saved. You can view the `gulpfile.js` to see which tasks are included with the dev environment.

### Gulp Tasks

* `gulp` the default task that builds everything
* `gulp watch` browserSync opens the project in your default browser and live reloads when changes are made
* `gulp css` compiles SCSS files into CSS and minifies the compiled CSS
* `gulp js` minifies the themes JS file
* `gulp vendor` copies dependencies from node_modules to the vendor directory

You must have npm installed globally in order to use this build environment. This theme was built using node v11.6.0 and the Gulp CLI v2.0.1. If Gulp is not running properly after running `npm install`, you may need to update node and/or the Gulp CLI locally.

## Bugs and Issues

Have a bug or an issue with this template? [Open a new issue](https://github.com/StartBootstrap/startbootstrap-sb-admin-2/issues) here on GitHub or leave a comment on the [template overview page at Start Bootstrap](https://startbootstrap.com/theme/sb-admin-2/).

## About

Start Bootstrap is an open source library of free Bootstrap templates and themes. All of the free templates and themes on Start Bootstrap are released under the MIT license, which means you can use them for any purpose, even for commercial projects.

* <https://startbootstrap.com>
* <https://twitter.com/SBootstrap>

Start Bootstrap was created by and is maintained by **[David Miller](https://davidmiller.io/)**.

* <https://davidmiller.io>
* <https://twitter.com/davidmillerhere>
* <https://github.com/davidtmiller>

Start Bootstrap is based on the [Bootstrap](https://getbootstrap.com/) framework created by [Mark Otto](https://twitter.com/mdo) and [Jacob Thorton](https://twitter.com/fat).

## Copyright and License

Copyright 2013-2021 Start Bootstrap LLC. Code released under the [MIT](https://github.com/StartBootstrap/startbootstrap-resume/blob/master/LICENSE) license.
