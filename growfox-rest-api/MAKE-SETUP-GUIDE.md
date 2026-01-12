# คู่มือตั้งค่า Make.com (รองรับ 48 สกุลเงิน)

## 🎯 วิธีที่ 1: ส่งทั้งหมดครั้งเดียว (แนะนำ)

### Module 1: HTTP - Get BoT API
**URL:**
```
https://www.bot.or.th/content/bot/th/statistics/exchange-rate/jcr:content/root/container/statisticstable2.results.level1cache.json
```
**Method:** GET

### Module 2: HTTP - Send to WordPress
**URL:** `https://yourdomain.com/wp-json/growfox/v1/exchange-rates`
**Method:** POST
**Body:** `{{1.responseContent}}`

### Module 3: Schedule
**Interval:** Every 6 hours

---

## 🎯 วิธีที่ 2: ส่งทีละสกุล (ยืดหยุ่นกว่า)

### Module 1: HTTP - Get BoT API
(เหมือนวิธีที่ 1)

### Module 2: Iterator
**Array:** `{{1.responseContent}}`

### Module 3: HTTP - Send Each Currency
**URL:** `https://yourdomain.com/wp-json/growfox/v1/exchange-rate`
**Method:** POST
**Body:**
```json
{
  "currency_id": "{{2.currency_id}}",
  "buying_transfer": "{{2.buying_transfer}}",
  "selling": "{{2.selling}}",
  "period": "{{2.period}}"
}
```

---

## 📝 ใช้ใน Elementor

1. ลาก Heading Widget
2. คลิก Dynamic Tag → Growfox → อัตราแลกเปลี่ยน
3. ใส่สกุลเงิน: `USD` (หรือ EUR, JPY, GBP...)
4. เลือกประเภท: อัตราซื้อ/ขาย

**ตัวอย่าง:**
- สกุลเงิน: `USD`
- ประเภท: อัตราขาย
- แสดงผล: `35.20`
