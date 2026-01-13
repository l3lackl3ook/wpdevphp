# 🚀 คู่มือใช้งาน Growfox REST API + Shortcodes

## สำหรับ siamfinancial.com (ไม่มี Elementor)

---

## ✅ สรุปสั้นๆ

**Plugin ที่ใช้:** `growfox-rest-api` (เหมือน blogeverydayth)

**ความแตกต่าง:**
- **blogeverydayth** → ใช้ Elementor → Dynamic Tags
- **siamfinancial** → ไม่ใช้ Elementor → Shortcodes

**ข้อมูลมาจาก:** Make.com (เหมือนกัน)

---

## 📦 การติดตั้ง

### ขั้นตอนที่ 1: ติดตั้ง Plugin

1. ไปที่ **WordPress Admin → Plugins → Add New → Upload**
2. เลือก `growfox-rest-api-with-shortcodes.zip` จาก Desktop
3. คลิก **Install Now**
4. คลิก **Activate**

### ขั้นตอนที่ 2: ตั้งค่า Make.com

ใช้ Make.com scenario เดียวกับ blogeverydayth:

**Webhook URL:**
```
POST https://siamfinancial.com/wp-json/growfox/v1/exchange-rates
```

**ตัวอย่าง JSON:**
```json
[
  {
    "currency_id": "USD",
    "period": "30/12/2568",
    "buying": "31.3489",
    "selling": "31.4215",
    "transfer": "31.7436"
  },
  {
    "currency_id": "EUR",
    "period": "30/12/2568",
    "buying": "36.7555",
    "selling": "36.8414",
    "transfer": "37.5016"
  }
]
```

---

## 🎯 Shortcodes ที่ใช้ได้

### 1. Ticker Banner (แบบเลื่อน)

```
[growfox_ticker]
```

**พารามิเตอร์:**
- `currencies` = สกุลเงินที่จะแสดง (ค่าเริ่มต้น: USD,EUR,GBP,JPY,CNY,HKD,SGD)
- `height` = ความสูง (ค่าเริ่มต้น: 44px)
- `speed` = ความเร็ว (ค่าเริ่มต้น: 50)
- `bg_color` = สีพื้นหลัง (ค่าเริ่มต้น: #1a5f4a)
- `text_color` = สีตัวอักษร (ค่าเริ่มต้น: #ffffff)
- `show_flags` = แสดงธงชาติ (yes/no)

**ตัวอย่าง:**
```
[growfox_ticker currencies="USD,EUR,GBP,JPY,CNY" height="44px" speed="50" bg_color="#667eea" text_color="#ffffff" show_flags="yes"]
```

---

### 2. ตารางอัตราแลกเปลี่ยน (แบบเต็ม)

```
[growfox_table]
```

**พารามิเตอร์:**
- `currencies` = สกุลเงินที่จะแสดง
- `show_flags` = แสดงธงชาติ (yes/no)
- `table_style` = สไตล์ตาราง (modern/classic/minimal)

**ตัวอย่าง:**
```
[growfox_table currencies="USD,EUR,GBP,JPY,CNY,HKD,SGD,MYR,AUD,NZD,CAD,CHF" show_flags="yes" table_style="modern"]
```

---

### 3. อัตราแลกเปลี่ยนเดี่ยว

```
[growfox_rate currency="USD" type="selling"]
```

**พารามิเตอร์:**
- `currency` = สกุลเงิน (USD, EUR, GBP, etc.)
- `type` = ประเภท (buying/selling/transfer)
- `format` = รูปแบบ (number/text)

**ตัวอย่าง:**
```
อัตราขาย USD: [growfox_rate currency="USD" type="selling"] บาท
```

---

## 📝 วิธีใช้งานในหน้า WordPress

### ตัวอย่างที่ 1: หน้าอัตราแลกเปลี่ยน (เหมือน blogeverydayth)

1. สร้างหน้าใหม่: **Pages → Add New**
2. ตั้งชื่อ: "อัตราแลกเปลี่ยนวันนี้"
3. เพิ่ม **Shortcode Block**
4. วางโค้ด:

```
[growfox_ticker currencies="USD,EUR,GBP,JPY,CNY,HKD,SGD" height="44px" bg_color="#667eea"]

[growfox_table currencies="USD,EUR,GBP,JPY,CNY,HKD,SGD,MYR,AUD,NZD,CAD,CHF,SEK,NOK,DKK,INR,IDR,PHP" show_flags="yes"]
```

5. **Publish**

---

### ตัวอย่างที่ 2: Ticker ใน Header (แสดงทุกหน้า)

ใช้ plugin **Insert Headers and Footers** หรือ **WPCode**:

```
[growfox_ticker currencies="USD,EUR,GBP,JPY,CNY" height="40px" bg_color="#1a5f4a"]
```

---

### ตัวอย่างที่ 3: แสดงอัตราในเนื้อหา

```
วันนี้อัตราแลกเปลี่ยน USD ขายที่ [growfox_rate currency="USD" type="selling"] บาท
```

---

## 🔄 การทดสอบว่ามีข้อมูลหรือยัง

### วิธีที่ 1: เปิด URL นี้

```
https://siamfinancial.com/wp-json/growfox/v1/exchange-rates
```

จะเห็น JSON ข้อมูลทั้งหมด

### วิธีที่ 2: ใช้ Browser Console

1. เปิด WordPress Admin
2. กด **F12** → แท็บ **Console**
3. วางโค้ด:

```javascript
fetch('/wp-json/growfox/v1/exchange-rates')
  .then(r => r.json())
  .then(data => console.log(data));
```

---

## 🆚 เปรียบเทียบ blogeverydayth vs siamfinancial

| ฟีเจอร์ | blogeverydayth | siamfinancial |
|---------|----------------|---------------|
| Plugin | growfox-rest-api | growfox-rest-api (เหมือนกัน) |
| Editor | Elementor | WordPress Block Editor |
| แสดงข้อมูล | Dynamic Tags | Shortcodes |
| ข้อมูลจาก | Make.com | Make.com (เหมือนกัน) |
| Ticker | Elementor Widget | `[growfox_ticker]` |
| ตาราง | Elementor Table | `[growfox_table]` |
| ธงชาติ | ✅ | ✅ |
| Real-time | ✅ | ✅ |

---

## 💡 Tips

1. **ใช้ Make.com scenario เดียวกัน** - ไม่ต้องสร้างใหม่
2. **Webhook URL เดียวกัน** - แค่เปลี่ยน domain
3. **ข้อมูลอัพเดทอัตโนมัติ** - เหมือน blogeverydayth
4. **ไม่ต้องใช้ Elementor** - ใช้ shortcode แทน

---

## 🐛 Troubleshooting

### ไม่แสดงข้อมูล?

1. ตรวจสอบว่า Make.com ส่งข้อมูลมาหรือยัง:
   ```
   https://siamfinancial.com/wp-json/growfox/v1/exchange-rates
   ```

2. ตรวจสอบ Make.com webhook URL ถูกต้องหรือไม่

3. ลอง trigger Make.com scenario ใหม่

### Ticker ไม่เลื่อน?

1. ตรวจสอบว่า jQuery โหลดแล้ว
2. ดู Console (F12) มี error หรือไม่
3. ลอง refresh หน้าเว็บ (Ctrl+F5)

---

## 📞 สรุป

**ไฟล์บน Desktop:**
- `growfox-rest-api-with-shortcodes.zip` - Plugin พร้อม shortcodes

**ขั้นตอน:**
1. ติดตั้ง plugin
2. ตั้งค่า Make.com (ใช้ scenario เดิม แค่เปลี่ยน URL)
3. ใส่ shortcode ในหน้า WordPress
4. เสร็จ! ข้อมูลจะอัพเดทอัตโนมัติจาก Make.com

**Shortcode หลัก:**
- `[growfox_ticker]` - Ticker banner
- `[growfox_table]` - ตารางเต็ม
- `[growfox_rate currency="USD" type="selling"]` - อัตราเดี่ยว

🎉 **ใช้งานได้เหมือน blogeverydayth แต่ไม่ต้องใช้ Elementor!**
