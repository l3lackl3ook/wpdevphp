# แก้ปัญหา Dropdown สกุลเงินว่างเปล่าใน Dynamic Tags

## ปัญหาที่พบ
- Dynamic Tags แสดงใน Elementor แล้ว ✅
- แต่ Dropdown สกุลเงินว่างเปล่า ❌
- ACF ยังไม่มีข้อมูล ❌

## สาเหตุ
1. Make.com ส่งข้อมูลมาสำเร็จ (Status 200)
2. แต่ข้อมูลถูกบันทึกเป็น JSON ใน ACF field `exchange_data`
3. Dynamic Tag ต้องอ่าน JSON แล้ว parse เป็น dropdown options
4. **ถ้ายังไม่มีข้อมูล dropdown จะว่าง**

## วิธีแก้ไข (v2.3.0)

### 1. อัปเดต Plugin
- ลบ plugin เดิม `growfox-rest-api-v2.2.zip`
- อัปโหลด `growfox-rest-api-v2.3.zip` ใหม่
- Activate plugin

### 2. ตรวจสอบ ACF Options
ไปที่: **WordPress Dashboard → อัตราแลกเปลี่ยน**

ถ้ายังไม่มีข้อมูล:
- ให้ Make.com ส่งข้อมูลมาอีกครั้ง
- หรือรอให้ Make.com ส่งข้อมูลอัตโนมัติ

### 3. ทดสอบ Dynamic Tags
1. เปิด Elementor Editor
2. เพิ่ม Widget Heading
3. คลิกไอคอน Dynamic Tags
4. เลือก **Growfox → อัตราแลกเปลี่ยน**
5. **ตอนนี้จะเห็นสกุลเงินให้เลือกแล้ว!** 🎉

## การทำงานของ v2.3.0

### ก่อนมีข้อมูล
- Dropdown จะแสดงสกุลเงินหลักๆ 20 สกุล (USD, EUR, GBP, JPY, ...)
- เลือกได้แต่จะแสดง `--` (รอข้อมูล)

### หลังมีข้อมูล
- Dropdown จะแสดงสกุลเงินทั้งหมดที่ Make.com ส่งมา (48 สกุล)
- แสดงข้อมูลจริงจาก ธปท.

## ตรวจสอบว่าข้อมูลถูกบันทึกหรือยัง

### วิธีที่ 1: ดูใน ACF Options
```
WordPress Dashboard → อัตราแลกเปลี่ยน
```
- ถ้ามีข้อมูล JSON ใน field "ข้อมูลอัตราแลกเปลี่ยน" = สำเร็จ ✅
- ถ้าว่าง = ยังไม่มีข้อมูล ❌

### วิธีที่ 2: ดู Debug Log
```
WordPress Dashboard → Tools → Site Health → Info → Server
```
หรือดูไฟล์ `wp-content/debug.log`:
```
[timestamp] Growfox API - Success: Updated 48 currencies
```

### วิธีที่ 3: ทดสอบส่งข้อมูลจาก Make.com
1. เปิด Make.com Scenario
2. คลิก "Run once"
3. ดูผลลัพธ์ HTTP module:
   - Status 200 = สำเร็จ ✅
   - Status 400 = ข้อมูลผิดพลาด ❌

## ถ้ายังไม่ได้

### ตรวจสอบ ACF Plugin
```bash
WordPress Dashboard → Plugins
```
- ต้องมี **Advanced Custom Fields** หรือ **ACF PRO** ติดตั้งและ Activate

### ตรวจสอบ Elementor
```bash
WordPress Dashboard → Plugins
```
- ต้องมี **Elementor** ติดตั้งและ Activate

### ตรวจสอบ Make.com Webhook
URL ต้องเป็น:
```
https://yourdomain.com/wp-json/growfox/v1/exchange-rates
```

Body ต้องเป็น:
```json
{
  "data": {
    "responseContent": [...]
  }
}
```

## สรุป
v2.3.0 แก้ปัญหา dropdown ว่างเปล่าโดย:
1. แสดงสกุลเงินหลักๆ ไว้ก่อน (ก่อนมีข้อมูล)
2. อัปเดตเป็นสกุลเงินจริงเมื่อมีข้อมูล
3. รองรับทั้งก่อนและหลังมีข้อมูล
