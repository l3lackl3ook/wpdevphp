# 📰 Siam Blog Layouts

Plugin สำหรับแสดงหน้า Blog และ Category แบบสวยงามเหมือน aommoney.com

---

## 🎯 ฟีเจอร์

✅ แสดงหมวดหมู่ทั้งหมดแบบ Grid พร้อมรูปภาพ  
✅ แสดงบทความในหมวดหมู่แบบ List  
✅ Latest/Popular Tabs - สลับดูบทความล่าสุด/ยอดนิยม  
✅ Pagination - แบ่งหน้าอัตโนมัติ  
✅ Responsive - รองรับทุกขนาดหน้าจอ  
✅ ใช้งานง่าย - แค่ใส่ Shortcode  

---

## 📦 ติดตั้ง

1. ไปที่ WordPress Admin → Plugins → Add New
2. คลิก **Upload Plugin**
3. เลือกไฟล์ `siam-blog-layouts.zip`
4. คลิก **Install Now**
5. คลิก **Activate**

---

## 🚀 วิธีใช้งาน

### 1️⃣ แสดงหมวดหมู่ทั้งหมด (Column Page)

สร้างหน้าใหม่ชื่อ "Blog" หรือ "Column" แล้วใส่:

```
[siam_categories]
```

**ตัวอย่างแบบกำหนดค่า:**

```
[siam_categories title="Column" description="เพิ่มความรู้และขยายมุมมองผ่านคอลัมน์สุดพิเศษ" orderby="count" order="DESC"]
```

**พารามิเตอร์:**
- `title` - หัวข้อหน้า (default: "Column")
- `description` - คำอธิบาย (รองรับ HTML)
- `exclude` - ID หมวดหมู่ที่ไม่ต้องการแสดง (เช่น "1,5,10")
- `orderby` - เรียงตาม: count (จำนวนโพสต์), name (ชื่อ), id
- `order` - ASC หรือ DESC

---

### 2️⃣ แสดงบทความในหมวดหมู่

สร้างหน้าใหม่สำหรับแต่ละหมวดหมู่ เช่น "การลงทุน" แล้วใส่:

```
[siam_posts category="การลงทุน"]
```

**ตัวอย่างแบบกำหนดค่า:**

```
[siam_posts category="การลงทุน" posts_per_page="12" show_tabs="yes" show_excerpt="yes"]
```

**พารามิเตอร์:**
- `category` - slug หรือ ID ของหมวดหมู่ (เช่น "การลงทุน" หรือ "5")
- `posts_per_page` - จำนวนโพสต์ต่อหน้า (default: 12)
- `show_tabs` - แสดง Latest/Popular tabs (yes/no, default: yes)
- `show_excerpt` - แสดงข้อความตัวอย่าง (yes/no, default: yes)
- `show_author` - แสดงชื่อผู้เขียน (yes/no, default: yes)
- `show_date` - แสดงวันที่ (yes/no, default: yes)
- `show_categories` - แสดงหมวดหมู่ (yes/no, default: yes)

---

## 📝 ตัวอย่างการใช้งาน

### สร้างหน้า "Blog" (แสดงหมวดหมู่ทั้งหมด)

1. Pages → Add New
2. ชื่อหน้า: **Blog**
3. เพิ่ม **Shortcode Block**
4. ใส่: `[siam_categories]`
5. Publish

### สร้างหน้า "การลงทุน" (แสดงบทความในหมวด)

1. Pages → Add New
2. ชื่อหน้า: **การลงทุน**
3. เพิ่ม **Shortcode Block**
4. ใส่: `[siam_posts category="การลงทุน"]`
5. Publish

---

## 🎨 Customization

### เปลี่ยนสี

ไปที่ Appearance → Customize → Additional CSS แล้วเพิ่ม:

```css
/* เปลี่ยนสีหลัก */
.tab-button.active,
.pagination .page-numbers.current {
    background: #your-color !important;
    border-color: #your-color !important;
}

/* เปลี่ยนสี hover */
.column-list .title-post a:hover,
.article-list .title-post a:hover {
    color: #your-color !important;
}
```

### เปลี่ยนขนาดรูปภาพ

```css
/* รูปหมวดหมู่ */
.column-list .img-post {
    height: 250px; /* เปลี่ยนจาก 200px */
}

/* รูปบทความ */
.article-list .img-post {
    width: 320px; /* เปลี่ยนจาก 280px */
    height: 200px; /* เปลี่ยนจาก 180px */
}
```

---

## 🔧 Tips

### 1. เพิ่มรูปภาพให้หมวดหมู่

ติดตั้ง plugin **Category Images** หรือ **Categories Images** เพื่อเพิ่มรูปภาพให้แต่ละหมวดหมู่

### 2. ทำให้บทความเป็น Popular

จำนวน Popular จะนับจาก **Comment Count** ดังนั้นควร:
- เปิดใช้งาน Comments
- ติดตั้ง plugin เช่น **Post Views Counter** เพื่อนับจำนวนการดู

### 3. SEO

- ตั้งชื่อหน้าให้ชัดเจน (เช่น "บทความการลงทุน")
- เพิ่ม Meta Description
- ใช้ plugin SEO เช่น Yoast SEO หรือ Rank Math

---

## 🐛 Troubleshooting

### ปัญหา: ไม่มีรูปภาพแสดง

**แก้ไข:**
1. ตรวจสอบว่าโพสต์มี Featured Image หรือไม่
2. ติดตั้ง plugin Category Images
3. ตรวจสอบ folder `assets/images/` มีไฟล์ default หรือไม่

### ปัญหา: Layout แตก

**แก้ไข:**
1. ตรวจสอบว่า theme มี CSS ที่ขัดแย้งหรือไม่
2. เพิ่ม `!important` ใน CSS
3. ลองปิด plugin อื่นๆ ที่เกี่ยวกับ CSS

### ปัญหา: Pagination ไม่ทำงาน

**แก้ไข:**
1. ไปที่ Settings → Reading
2. ตั้งค่า "Blog pages show at most" เป็นจำนวนที่ต้องการ
3. ไปที่ Settings → Permalinks
4. คลิก Save Changes (ไม่ต้องเปลี่ยนอะไร)

---

## 📞 Support

หากมีปัญหาหรือข้อสงสัย:
- Email: support@siamfinancial.com
- Website: https://siamfinancial.com

---

## 📄 License

GPL v2 or later

---

## 🎉 เสร็จแล้ว!

ตอนนี้คุณมีหน้า Blog สวยงามเหมือน aommoney.com แล้ว!
