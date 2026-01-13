# คู่มือการใช้งาน Ticker Banner

## 📊 Ticker Banner แบบลงทุนแมน

Plugin Thailand Financial Data รองรับการแสดงข้อมูลแบบ ticker banner เลื่อนอัตโนมัติ เหมือนกับที่ใช้ในเว็บลงทุนแมน

---

## 🚀 วิธีใช้งาน

### 1. แบบพื้นฐาน (Basic)

ใส่ shortcode นี้ในหน้า WordPress ของคุณ:

```
[tfd_ticker_banner]
```

จะแสดงข้อมูลทั้งหมด: อัตราแลกเปลี่ยน, ราคาทอง, ราคาน้ำมัน

---

### 2. กำหนดความสูง (Custom Height)

```
[tfd_ticker_banner height="50px"]
```

---

### 3. เลือกประเภทข้อมูลที่จะแสดง

```
[tfd_ticker_banner items="exchange"]
```

แสดงเฉพาะอัตราแลกเปลี่ยน

```
[tfd_ticker_banner items="gold,oil"]
```

แสดงเฉพาะราคาทองและน้ำมัน

```
[tfd_ticker_banner items="exchange,gold,oil"]
```

แสดงทั้งหมด (ค่าเริ่มต้น)

---

### 4. ปรับแต่งสี (Custom Colors)

```
[tfd_ticker_banner bg_color="#1a5f4a" text_color="#ffffff"]
```

- `bg_color` = สีพื้นหลัง (เขียว เหมือนลงทุนแมน)
- `text_color` = สีตัวอักษร

---

### 5. ปรับความเร็ว (Custom Speed)

```
[tfd_ticker_banner speed="30"]
```

- ค่าเริ่มต้น: `50` (px/s)
- ยิ่งน้อย = เลื่อนช้า
- ยิ่งมาก = เลื่อนเร็ว

---

## 🎨 ตัวอย่างการใช้งานแบบเต็ม

```
[tfd_ticker_banner height="44px" items="exchange,gold,oil" speed="50" bg_color="#1a5f4a" text_color="#ffffff"]
```

---

## 📝 วิธีใส่ใน WordPress

### วิธีที่ 1: ใช้ Shortcode Block (แนะนำ)

1. เปิดหน้าที่ต้องการแก้ไขใน WordPress Editor
2. คลิก **+** เพื่อเพิ่ม Block
3. ค้นหา **"Shortcode"**
4. วาง shortcode: `[tfd_ticker_banner]`
5. คลิก **Update** หรือ **Publish**

### วิธีที่ 2: ใช้ Custom HTML Block

1. เปิดหน้าที่ต้องการแก้ไขใน WordPress Editor
2. คลิก **+** เพื่อเพิ่ม Block
3. ค้นหา **"Custom HTML"**
4. วาง shortcode: `[tfd_ticker_banner]`
5. คลิก **Update** หรือ **Publish**

### วิธีที่ 3: ใช้ใน Elementor

1. เปิดหน้าด้วย Elementor
2. ลาก **Shortcode Widget** มาวาง
3. วาง shortcode: `[tfd_ticker_banner]`
4. คลิก **Update**

### วิธีที่ 4: ใส่ใน Header/Footer (แสดงทุกหน้า)

ใช้ plugin เช่น:
- **Insert Headers and Footers**
- **WPCode**
- **Code Snippets**

แล้ววาง shortcode ในส่วน Header หรือ Footer

---

## 🎯 ตัวอย่างผลลัพธ์

Ticker banner จะแสดงข้อมูลแบบเลื่อนอัตโนมัติ:

```
USD: 35.50 | EUR: 38.20 | JPY: 0.24 | ทองคำแท่ง ซื้อ: 28,500 | ทองคำแท่ง ขาย: 28,600 | แก๊สโซฮอล์ 91: 35.50 | ดีเซล: 32.00
```

---

## ⚙️ ฟีเจอร์พิเศษ

- ✅ เลื่อนอัตโนมัติแบบ seamless loop
- ✅ หยุดชั่วคราวเมื่อ hover เมาส์
- ✅ Responsive (ปรับขนาดตามหน้าจอ)
- ✅ ปรับแต่งสีและความเร็วได้
- ✅ เลือกแสดงเฉพาะข้อมูลที่ต้องการ

---

## 🔄 การอัพเดทข้อมูล

ข้อมูลจะอัพเดทอัตโนมัติผ่าน Make.com webhook ตามที่ตั้งค่าไว้

หากต้องการ refresh ข้อมูลทันที:
1. ไปที่ **WordPress Admin → Thailand Financial Data**
2. คลิก **Refresh Data**

---

## 🆚 เปรียบเทียบกับ iframe ของลงทุนแมน

| ฟีเจอร์ | iframe ลงทุนแมน | TFD Ticker Banner |
|---------|-----------------|-------------------|
| ข้อมูล | จาก Webull | จาก Make.com (ของคุณเอง) |
| ปรับแต่ง | จำกัด | ปรับแต่งได้เต็มที่ |
| ความเร็ว | โหลดช้า (external) | โหลดเร็ว (local) |
| SEO | ไม่ดี (iframe) | ดีกว่า (native HTML) |
| ควบคุม | ไม่ได้ | ควบคุมเต็มที่ |

---

## 🐛 Troubleshooting

### ไม่แสดงข้อมูล?

1. ตรวจสอบว่ามีข้อมูลใน **Thailand Financial Data** admin page
2. ตรวจสอบว่า Make.com webhook ทำงานปกติ
3. ลอง refresh หน้าเว็บ (Ctrl+F5)

### Ticker ไม่เลื่อน?

1. ตรวจสอบว่า jQuery โหลดแล้ว
2. ดูใน Console (F12) มี error หรือไม่
3. ลองปิด plugin อื่นที่อาจขัดแย้ง

---

## 💡 Tips

- ใช้ `height="44px"` เพื่อให้เหมือนลงทุนแมนพอดี
- ใช้ `bg_color="#1a5f4a"` เพื่อให้สีเขียนเหมือนลงทุนแมน
- ใช้ `speed="50"` เพื่อความเร็วที่พอดี ไม่เร็วหรือช้าเกินไป
- วาง ticker ไว้ที่ header เพื่อแสดงทุกหน้า

---

## 📞 ต้องการความช่วยเหลือ?

หากมีปัญหาหรือต้องการปรับแต่งเพิ่มเติม สามารถติดต่อได้ที่:
- Email: support@aommoney.com
- GitHub: https://github.com/l3lackl3ook/wpdevphp
