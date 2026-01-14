# 🏆 คู่มือตั้งค่าราคาทองคำ

## สำหรับ siamfinancial.com

---

## 📦 ติดตั้ง Plugin

1. Deactivate + Delete plugin เก่า
2. Upload `growfox-with-gold.zip`
3. Activate

---

## 🔧 ตั้งค่า Make.com

### Scenario ราคาทอง (มีอยู่แล้ว)

**HTTP Module 1** (ดึงข้อมูล):
- URL: `https://xn--42cah7d0cxcvbbb9x.com` (ราคาทองคำ.com)
- Method: GET

**Text Parser** (แยกข้อมูล):
- แยกข้อมูล: ซื้อ, ขาย, เปลี่ยนแปลง, วันที่, เวลา

**HTTP Module 2** (ส่งไป blogeverydayth):
- URL: `https://blogeverydayth.com/wp-json/thailand-financial/v1/gold-prices`
- Method: POST
- Body: JSON

**HTTP Module 3** (ส่งไป siamfinancial) - **เพิ่มใหม่**:
- URL: `https://siamfinancial.com/wp-json/growfox/v1/gold-prices`
- Method: POST
- Headers:
  - Name: `Content-Type`
  - Value: `application/json`
- Body type: `Raw`
- Content type: `JSON (application/json)`
- Request content:
```json
{
  "bar_buy": "{{ราคาซื้อ}}",
  "bar_sell": "{{ราคาขาย}}",
  "change": "{{เปลี่ยนแปลง}}",
  "date": "{{วันที่}}",
  "time": "{{เวลา}}"
}
```

---

## 📝 ใช้งาน Shortcode

### แสดง Card ราคาทอง

```
[growfox_gold]
```

### วางตรงกลางหน้า Homepage

1. เปิดหน้า Homepage ใน WordPress Editor
2. เพิ่ม **Shortcode Block** ระหว่างส่วน "การเงินกับเศรษฐกิจไทย"
3. วาง:
```
[growfox_gold]
```
4. **Update**

---

## 🎨 ตัวอย่าง Layout

```
┌─────────────────────────────────────┐
│   [Ticker Banner - อัตราแลกเปลี่ยน]  │
└─────────────────────────────────────┘

┌─────────────────────────────────────┐
│  การเงินกับเศรษฐกิจไทย               │
└─────────────────────────────────────┘

┌─────────────────────────────────────┐
│   [growfox_gold] ← ใส่ตรงนี้         │
│   ราคาทองคำวันนี้                    │
│   ┌──────────┬──────────┐           │
│   │ ซื้อ     │ ขาย      │           │
│   │ 68,100   │ 68,200   │           │
│   └──────────┴──────────┘           │
│   วันนี้ +500                        │
└─────────────────────────────────────┘
```

---

## ✅ ทดสอบว่าข้อมูลเข้าหรือยัง

### วิธีที่ 1: เปิด URL

```
https://siamfinancial.com/wp-json/growfox/v1/gold-prices
```

ถ้ามีข้อมูลจะเห็น:
```json
{
  "success": true,
  "last_update": "2026-01-13 17:21:00",
  "data": {
    "bar_buy": "68100",
    "bar_sell": "68200",
    "change": "500",
    "date": "13/01/2026",
    "time": "17:21"
  }
}
```

### วิธีที่ 2: Run Make.com

1. เปิด Make.com scenario
2. คลิก **Run once**
3. ดูผลลัพธ์ HTTP Module 3
4. ถ้าสำเร็จจะเห็น `"success": true`

---

## 🎯 ฟีเจอร์

✅ แสดงราคาซื้อ-ขาย  
✅ แสดงเปลี่ยนแปลง (+/-) พร้อมสี  
✅ แสดงวันที่และเวลา  
✅ Responsive (มือถือ/แท็บเล็ต/คอมพิวเตอร์)  
✅ สีเขียว = ขึ้น, สีแดง = ลง  
✅ ดีไซน์สวยงามเหมือน aommoney  

---

## 🔄 การอัพเดทข้อมูล

ข้อมูลจะอัพเดทอัตโนมัติตาม Make.com schedule:
- ทุก 30 นาที (แนะนำ)
- ทุก 1 ชั่วโมง
- หรือตามที่ตั้งค่า

---

## 🐛 แก้ไขปัญหาที่พบ

### ปัญหา: ราคาแสดงเป็น "68" แทนที่จะเป็น "68,750"

**สาเหตุ:** ข้อมูลจาก Make.com มี comma อยู่แล้ว (เช่น "68,750.00") แต่ PHP `number_format()` ไม่สามารถแปลงได้

**แก้ไข:** Plugin ได้แก้ไขให้ลบ comma ออกก่อนแปลงเป็นตัวเลขแล้ว

**Text Parser Pattern ที่ถูกต้อง:**
- Text Parser 4 (ทองรูปพรรณ - รับซื้อ):
  ```
  ทองรูปพรรณ</td>\s*<td[^>]*>([0-9,]+\.?[0-9]*)</td>
  ```
- Text Parser 5 (ทองรูปพรรณ - ขายออก):
  ```
  ทองรูปพรรณ</td>\s*<td[^>]*>[0-9,]+\.?[0-9]*</td>\s*<td[^>]*>([0-9,]+\.?[0-9]*)</td>
  ```

Pattern นี้รองรับทั้งราคาที่มีและไม่มีทศนิยม

---

## 💡 Tips

- ใส่ shortcode `[growfox_gold]` ระหว่างส่วนเนื้อหา
- ใช้ **Columns Block** ถ้าต้องการวางข้างๆ กัน
- Card จะ center อัตโนมัติ (max-width: 600px)

---

## 📦 ไฟล์บน Desktop

`growfox-with-gold.zip` - Plugin พร้อมฟีเจอร์ราคาทอง

---

## 🎉 เสร็จแล้ว!

ตอนนี้คุณมี:
1. ✅ Ticker Banner อัตราแลกเปลี่ยน
2. ✅ Card ราคาทองคำ
3. ✅ ตารางอัตราแลกเปลี่ยนแบบเต็ม

ทั้งหมดอัพเดทอัตโนมัติจาก Make.com!
