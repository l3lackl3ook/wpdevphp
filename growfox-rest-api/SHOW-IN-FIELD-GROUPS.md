# วิธีทำให้เห็น Field Groups ใน ACF Admin

## ปัญหา
- ไปที่ ACF → Field Groups แล้วไม่เห็นอะไร
- เพราะใช้ `acf_add_local_field_group()` (Local = ไม่บันทึกในฐานข้อมูล)

## วิธีแก้

### วิธีที่ 1: ดูในหน้า Options Page (แนะนำ)
```
WordPress Dashboard → อัตราแลกเปลี่ยน (เมนูด้านซ้าย)
```
จะเห็น Fields ทั้ง 3 ตัว:
- ข้อมูลอัตราแลกเปลี่ยน (JSON)
- วันที่
- อัปเดตล่าสุด

### วิธีที่ 2: Sync to Database (ไม่แนะนำ)

1. ติดตั้ง ACF PRO (ต้องซื้อ)
2. ไปที่ ACF → Tools → Import Field Groups
3. เลือกไฟล์ JSON

**ข้อเสีย:**
- ต้องซื้อ ACF PRO
- ถ้าแก้โค้ด ต้อง Sync ใหม่
- ซับซ้อนกว่า

### วิธีที่ 3: สร้างใหม่ใน Admin (ไม่แนะนำ)

1. ไปที่ ACF → Field Groups → Add New
2. สร้าง Fields เหมือนในโค้ด
3. ลบโค้ด `acf_add_local_field_group()` ออก

**ข้อเสีย:**
- ต้องสร้างด้วยมือ
- ถ้า Plugin ถูกลบ Fields หาย
- ไม่สามารถ version control ได้

## สรุป

**ใช้วิธีที่ 1 ดีที่สุด:**
- ไม่ต้องแก้อะไร
- ดูข้อมูลได้ที่ WordPress Dashboard → อัตราแลกเปลี่ยน
- Fields ทำงานปกติ

**Local Field Groups ดีกว่าเพราะ:**
- เก็บในโค้ด (version control ได้)
- ลบ Plugin แล้วไม่เหลือขยะในฐานข้อมูล
- แก้ไขง่าย (แก้โค้ดเดียว)
- Performance ดีกว่า (ไม่ต้อง query database)
