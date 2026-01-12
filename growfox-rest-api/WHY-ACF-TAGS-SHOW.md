# ทำไม ACF Dynamic Tags ถึงแสดงใน Elementor?

## คำถาม
ทำไมใน Elementor Dynamic Tags มี "ACF Field" แสดงอยู่ด้วย? แล้วทำไมเลือกแล้วไม่มีข้อมูล?

## คำตอบ

### ACF Plugin สร้าง Dynamic Tags ให้อัตโนมัติ

```
ACF Plugin ติดตั้งแล้ว
    ↓
สร้าง Dynamic Tags Category "ACF" อัตโนมัติ
    ↓
แสดง ACF Fields ทั้งหมดที่มีในเว็บ
```

**ไม่ใช่ Growfox Plugin สร้าง!**

---

## Dynamic Tags ที่เห็นมาจาก 2 แหล่ง

### 1. ACF Dynamic Tags (จาก ACF Plugin)
```
Dynamic Tags
├── ACF
│   └── ACF Field
│       ├── ข้อมูลอัตราแลกเปลี่ยน
│       ├── ข้อมูลอัตราแลกเปลี่ยน (JSON)
│       ├── วันที่
│       └── อัปเดตล่าสุด
```

**ปัญหา:**
- แสดง JSON raw (ไม่สวย)
- ไม่สามารถเลือกสกุลเงินได้
- ไม่สามารถ format ตัวเลขได้

### 2. Growfox Dynamic Tags (จาก Growfox Plugin)
```
Dynamic Tags
├── Growfox
│   ├── อัตราแลกเปลี่ยน
│   └── วันที่อัตราแลกเปลี่ยน
```

**ข้อดี:**
- เลือกสกุลเงินได้ (48 สกุล)
- เลือกประเภทข้อมูลได้ (ซื้อ/ขาย)
- Format ตัวเลขสวยงาม (32.4107)

---

## ทำไม ACF Field ไม่แสดงข้อมูล?

### เหตุผล:
1. **ACF Fields ถูกลบออกแล้ว** (ไม่จำเป็น)
2. ACF Plugin ยังคงแสดง Dynamic Tags (เพราะไม่รู้ว่า Fields ถูกลบ)
3. เลือกแล้วไม่มีข้อมูล → เพราะ Fields ไม่มีอยู่จริง

---

## วิธีแก้ (v2.5.0)

### ซ่อน ACF Dynamic Tags ที่ไม่จำเป็น

Plugin v2.5.0 เพิ่มไฟล์ `hide-acf-tags.php`:
- กรอง ACF Field Groups ออกจาก Elementor
- ทำให้เห็นแค่ Growfox Dynamic Tags

### ผลลัพธ์:

**ก่อน (v2.4.0):**
```
Dynamic Tags
├── ACF
│   └── ACF Field (ไม่มีข้อมูล) ❌
└── Growfox
    └── อัตราแลกเปลี่ยน ✅
```

**หลัง (v2.5.0):**
```
Dynamic Tags
└── Growfox
    └── อัตราแลกเปลี่ยน ✅
```

---

## สรุป

### คำถาม: ทำไม ACF Field ถึงแสดง?
**คำตอบ:** ACF Plugin สร้างให้อัตโนมัติ (ไม่ใช่ Growfox)

### คำถาม: ทำไมเลือกแล้วไม่มีข้อมูล?
**คำตอบ:** เพราะ ACF Fields ถูกลบออกแล้ว (ไม่จำเป็น)

### คำถาม: ควรใช้อันไหน?
**คำตอบ:** ใช้ Growfox Dynamic Tags เท่านั้น ✅

### คำถาม: จะซ่อน ACF Tags ได้ไหม?
**คำตอบ:** ได้! อัปเดตเป็น v2.5.0 ✅

---

## การใช้งานที่ถูกต้อง

### ✅ ใช้ Growfox Dynamic Tags
```
Elementor → Dynamic Tags → Growfox → อัตราแลกเปลี่ยน
- เลือกสกุลเงิน: USD
- เลือกข้อมูล: อัตราขาย
→ แสดง: 32.4107
```

### ❌ อย่าใช้ ACF Dynamic Tags
```
Elementor → Dynamic Tags → ACF → ACF Field
→ ไม่มีข้อมูล หรือแสดง JSON raw
```
