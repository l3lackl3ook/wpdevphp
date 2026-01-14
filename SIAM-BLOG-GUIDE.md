# 📰 คู่มือใช้งาน Siam Blog Layouts Plugin

## สำหรับ siamfinancial.com

---

## 📦 ติดตั้ง Plugin

1. ไปที่ WordPress Admin → **Plugins** → **Add New**
2. คลิก **Upload Plugin**
3. เลือกไฟล์ `siam-blog-layouts.zip` จาก Desktop
4. คลิก **Install Now**
5. คลิก **Activate**

---

## 🎯 Shortcodes ที่ใช้ได้

### 1️⃣ `[siam_categories]` - แสดงหมวดหมู่ทั้งหมด

แสดงหมวดหมู่ทั้งหมดแบบ Grid เหมือนหน้า Column ของ aommoney.com

**ตัวอย่าง:**
```
[siam_categories]
```

**พารามิเตอร์:**
```
[siam_categories 
    title="Column" 
    description="เพิ่มความรู้และขยายมุมมองผ่านคอลัมน์สุดพิเศษ<br>ที่เขียนโดยนักเขียนมากประสบการณ์ในหลากหลายประเด็น"
    orderby="count" 
    order="DESC"
    exclude="1,5"]
```

- `title` - หัวข้อหน้า (default: "Column")
- `description` - คำอธิบาย (รองรับ HTML)
- `exclude` - ID หมวดหมู่ที่ไม่ต้องการแสดง
- `orderby` - เรียงตาม: count, name, id
- `order` - ASC หรือ DESC

---

### 2️⃣ `[siam_posts]` - แสดงบทความในหมวดหมู่

แสดงบทความในหมวดหมู่พร้อม Latest/Popular tabs

**ตัวอย่าง:**
```
[siam_posts category="การลงทุน"]
```

**พารามิเตอร์:**
```
[siam_posts 
    category="การลงทุน" 
    posts_per_page="12" 
    show_tabs="yes" 
    show_excerpt="yes"
    show_author="yes"
    show_date="yes"
    show_categories="yes"]
```

- `category` - slug หรือ ID ของหมวดหมู่
- `posts_per_page` - จำนวนโพสต์ต่อหน้า (default: 12)
- `show_tabs` - แสดง Latest/Popular tabs (yes/no)
- `show_excerpt` - แสดงข้อความตัวอย่าง (yes/no)
- `show_author` - แสดงชื่อผู้เขียน (yes/no)
- `show_date` - แสดงวันที่ (yes/no)
- `show_categories` - แสดงหมวดหมู่ (yes/no)

---

## 📝 ขั้นตอนการสร้างหน้า Blog

### สร้างหน้า "Blog" (แสดงหมวดหมู่ทั้งหมด)

1. ไปที่ **Pages** → **Add New**
2. ชื่อหน้า: **Blog**
3. คลิกปุ่ม **+** เพื่อเพิ่ม Block
4. ค้นหา **Shortcode**
5. เลือก **Shortcode Block**
6. ใส่: `[siam_categories]`
7. คลิก **Publish**

**URL:** `https://siamfinancial.com/blog/`

---

### สร้างหน้า "การลงทุน" (แสดงบทความในหมวด)

1. ไปที่ **Pages** → **Add New**
2. ชื่อหน้า: **การลงทุน**
3. คลิกปุ่ม **+** เพื่อเพิ่ม Block
4. ค้นหา **Shortcode**
5. เลือก **Shortcode Block**
6. ใส่: `[siam_posts category="การลงทุน"]`
7. คลิก **Publish**

**URL:** `https://siamfinancial.com/การลงทุน/`

---

### สร้างหน้าสำหรับหมวดหมู่อื่นๆ

ทำซ้ำขั้นตอนเดียวกัน แต่เปลี่ยน `category`:

```
[siam_posts category="จิตวิทยา"]
[siam_posts category="การเงินส่วนบุคคล"]
[siam_posts category="กองทุนรวม"]
[siam_posts category="หุ้น"]
[siam_posts category="ภาษี"]
[siam_posts category="ประกัน"]
[siam_posts category="ออมเงิน"]
```

---

## 🎨 ตัวอย่าง Layout

### หน้า Blog (Column)

```
┌─────────────────────────────────────────────┐
│              Column                         │
│  เพิ่มความรู้และขยายมุมมองผ่านคอลัมน์...   │
└─────────────────────────────────────────────┘

┌──────────┐ ┌──────────┐ ┌──────────┐ ┌──────────┐
│  [รูป]   │ │  [รูป]   │ │  [รูป]   │ │  [รูป]   │
│ 632 Art. │ │ 102 Art. │ │ 888 Art. │ │  15 Art. │
│ การลงทุน │ │ จิตวิทยา │ │ การเงิน  │ │ กองทุนรวม│
└──────────┘ └──────────┘ └──────────┘ └──────────┘
```

### หน้า Category (การลงทุน)

```
┌─────────────────────────────────────────────┐
│  การลงทุน          [Lastest] [Popular]     │
└─────────────────────────────────────────────┘

┌──────────┐  บลจ.กรุงศรี โชว์ผลงานปี 2568...
│  [รูป]   │  กองบรรณาธิการ • November 21, 2025
│          │  นางสุภาพร ลีนะบรรจง กรรมการผู้จัดการ...
└──────────┘  [Advertorial] [กองบรรณาธิการ]

┌──────────┐  หุ้นกู้ TRUE ทางเลือกกระจายความเสี่ยง...
│  [รูป]   │  ลงทุนศาสตร์ • October 22, 2025
│          │  ไม่มีใครกล้าบอกได้ว่าตอนนี้ราคาหุ้น...
└──────────┘  [Advertorial] [ลงทุนศาสตร์]
```

---

## 🔧 เพิ่มรูปภาพให้หมวดหมู่

Plugin จะดึงรูปจากโพสต์ล่าสุดในหมวดนั้นอัตโนมัติ แต่ถ้าต้องการกำหนดรูปเอง:

### วิธีที่ 1: ใช้ Plugin Category Images

1. ติดตั้ง plugin **Categories Images**
2. ไปที่ **Posts** → **Categories**
3. แก้ไขหมวดหมู่
4. อัปโหลดรูปภาพ
5. Save

### วิธีที่ 2: ให้โพสต์มี Featured Image

1. แก้ไขโพสต์ในหมวดนั้น
2. เพิ่ม **Featured Image**
3. Update

---

## 🎨 Customization

### เปลี่ยนสีหลัก

ไปที่ **Appearance** → **Customize** → **Additional CSS**

```css
/* เปลี่ยนสีปุ่ม Active */
.tab-button.active,
.pagination .page-numbers.current {
    background: #3498db !important;
    border-color: #3498db !important;
}

/* เปลี่ยนสี Hover */
.column-list .title-post a:hover,
.article-list .title-post a:hover,
.author-date-wrapper .author {
    color: #3498db !important;
}
```

### เปลี่ยนขนาดตัวอักษร

```css
/* หัวข้อหมวดหมู่ */
.column-list .title-post {
    font-size: 22px !important;
}

/* หัวข้อบทความ */
.article-list .title-post {
    font-size: 24px !important;
}
```

### เปลี่ยนขนาดรูปภาพ

```css
/* รูปหมวดหมู่ */
.column-list .img-post {
    height: 250px !important;
}

/* รูปบทความ */
.article-list .img-post {
    width: 320px !important;
    height: 200px !important;
}
```

---

## 🚀 Tips

### 1. เพิ่มหมวดหมู่ใหม่

1. ไปที่ **Posts** → **Categories**
2. คลิก **Add New Category**
3. ใส่ชื่อหมวดหมู่ (เช่น "Crypto")
4. ใส่ Slug (เช่น "crypto")
5. เพิ่มรูปภาพ (ถ้ามี plugin Category Images)
6. คลิก **Add New Category**

### 2. ทำให้บทความเป็น Popular

จำนวน Popular นับจาก **Comment Count**:
- เปิดใช้งาน Comments ในโพสต์
- หรือติดตั้ง plugin **Post Views Counter**

### 3. เพิ่มใน Menu

1. ไปที่ **Appearance** → **Menus**
2. เลือก Menu ที่ต้องการ
3. เพิ่มหน้า "Blog" เข้าไป
4. Save Menu

---

## 🐛 Troubleshooting

### ปัญหา: Shortcode แสดงเป็นข้อความ

**แก้ไข:** ตรวจสอบว่า Plugin ถูก Activate แล้วหรือยัง

### ปัญหา: ไม่มีรูปภาพแสดง

**แก้ไข:**
1. ตรวจสอบว่าโพสต์มี Featured Image
2. ติดตั้ง plugin Category Images
3. อัปโหลดรูป default ใน `wp-content/plugins/siam-blog-layouts/assets/images/`

### ปัญหา: Layout แตก

**แก้ไข:**
1. ตรวจสอบว่า theme มี CSS ที่ขัดแย้ง
2. เพิ่ม `!important` ใน Custom CSS
3. ลองเปลี่ยน theme เป็น default (Twenty Twenty-Four)

### ปัญหา: Pagination ไม่ทำงาน

**แก้ไข:**
1. ไปที่ **Settings** → **Permalinks**
2. คลิก **Save Changes** (ไม่ต้องเปลี่ยนอะไร)
3. ลองใหม่

---

## 📦 ไฟล์บน Desktop

- `siam-blog-layouts.zip` - Plugin พร้อมใช้งาน

---

## 🎉 เสร็จแล้ว!

ตอนนี้คุณมี:
1. ✅ หน้า Blog แสดงหมวดหมู่ทั้งหมด
2. ✅ หน้า Category แสดงบทความพร้อม Latest/Popular
3. ✅ Pagination อัตโนมัติ
4. ✅ Responsive ทุกขนาดหน้าจอ
5. ✅ ดีไซน์สวยงามเหมือน aommoney.com

**หน้าเว็บพร้อมใช้งานแล้ว!** 🚀
