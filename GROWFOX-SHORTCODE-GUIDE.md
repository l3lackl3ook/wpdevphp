# 🚀 คู่มือใช้งาน Growfox REST API + Shortcodes

## สำหรับ siamfinancial.com และ synergycrafted.com

---

## ✅ สรุปสั้นๆ

**Plugin ที่ใช้:** `growfox-rest-api` v3.0

**ฟีเจอร์:**
- ✅ อัตราแลกเปลี่ยน (Exchange Rates)
- ✅ ราคาทองคำ (Gold Prices)
- ✅ ราคาน้ำมัน (Oil Prices)

**ข้อมูลมาจาก:** Make.com

---

## 📦 การติดตั้ง

### ขั้นตอนที่ 1: ติดตั้ง Plugin

1. ไปที่ **WordPress Admin → Plugins → Add New → Upload**
2. เลือก `growfox-rest-api-v3.zip`
3. คลิก **Install Now**
4. คลิก **Activate**

### ขั้นตอนที่ 2: ตั้งค่า Make.com

ดูคู่มือแยกตามประเภท:
- **อัตราแลกเปลี่ยน**: ดูไฟล์ `MAKE-SETUP-GUIDE.md`
- **ราคาทองคำ**: ดูไฟล์ `GOLD-PRICE-SETUP.md`
- **ราคาน้ำมัน**: ดูไฟล์ `GROWFOX-OIL-SETUP-MAKE.md`

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

### 4. ราคาทองคำ (Card)

```
[growfox_gold]
```

**แสดง:**
- ราคาทองคำแท่ง (รับซื้อ/ขายออก)
- วันที่และเวลา
- การเปลี่ยนแปลง (+/-)
- รูปแบบสวยงามตาม aommoney.com

---

### 5. ราคาน้ำมัน (Table)

```
[growfox_oil]
```

**แสดง:**
- ตารางราคาน้ำมันทุกชนิด
- ราคาวันนี้และพรุ่งนี้
- รูปภาพแต่ละชนิดน้ำมัน
- ธีมสีเขียวตาม Bangchak

---

## 📝 วิธีใช้งานในหน้า WordPress

### ตัวอย่างที่ 1: หน้าอัตราแลกเปลี่ยน (siamfinancial.com)

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

### ตัวอย่างที่ 2: หน้าราคาทองคำ (siamfinancial.com)

1. สร้างหน้าใหม่
2. เพิ่ม **Shortcode Block**
3. วางโค้ด:

```
[growfox_gold]
```

---

### ตัวอย่างที่ 3: หน้าราคาน้ำมัน (synergycrafted.com)

1. สร้างหน้าใหม่
2. เพิ่ม **Shortcode Block** (Kubio Theme)
3. วางโค้ด:

```
[growfox_oil]
```

---

### ตัวอย่างที่ 4: Ticker ใน Header (แสดงทุกหน้า)

ใช้ plugin **Insert Headers and Footers** หรือ **WPCode**:

```
[growfox_ticker currencies="USD,EUR,GBP,JPY,CNY" height="40px" bg_color="#1a5f4a"]
```

---

## 🔄 การทดสอบว่ามีข้อมูลหรือยัง

### อัตราแลกเปลี่ยน

```
GET https://siamfinancial.com/wp-json/growfox/v1/exchange-rates
```

### ราคาทองคำ

```
GET https://siamfinancial.com/wp-json/growfox/v1/gold-prices
```

### ราคาน้ำมัน

```
GET https://synergycrafted.com/wp-json/growfox/v1/oil-prices
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
5. **รองรับ Kubio Theme** - ใช้ Shortcode Block

---

## 🐛 Troubleshooting

### ไม่แสดงข้อมูล?

1. ตรวจสอบว่า Make.com ส่งข้อมูลมาหรือยัง (ใช้ GET endpoint)
2. ตรวจสอบ Make.com webhook URL ถูกต้องหรือไม่
3. ลอง trigger Make.com scenario ใหม่
4. ตรวจสอบ WordPress Error Log

### Ticker ไม่เลื่อน?

1. ตรวจสอบว่า jQuery โหลดแล้ว
2. ดู Console (F12) มี error หรือไม่
3. ลอง refresh หน้าเว็บ (Ctrl+F5)

### Plugin ไม่ activate ได้?

1. ตรวจสอบ PHP version (ต้อง 7.4+)
2. ดู error log ใน wp-content/debug.log
3. ลองปิด plugin อื่นๆ ก่อน

---

## 📞 สรุป

**ไฟล์:**
- `growfox-rest-api-v3.zip` - Plugin พร้อม shortcodes

**Shortcode ทั้งหมด:**
- `[growfox_ticker]` - Ticker banner อัตราแลกเปลี่ยน
- `[growfox_table]` - ตารางอัตราแลกเปลี่ยนเต็ม
- `[growfox_rate currency="USD" type="selling"]` - อัตราเดี่ยว
- `[growfox_gold]` - Card ราคาทองคำ
- `[growfox_oil]` - ตารางราคาน้ำมัน

**REST API Endpoints:**
- `/wp-json/growfox/v1/exchange-rates` (GET/POST)
- `/wp-json/growfox/v1/gold-prices` (GET/POST)
- `/wp-json/growfox/v1/oil-prices` (GET/POST)

🎉 **ใช้งานได้ทั้ง 3 ประเภทข้อมูลในปลักอินเดียว!**
