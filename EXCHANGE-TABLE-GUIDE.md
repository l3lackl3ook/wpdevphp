# 📊 คู่มือการใช้งานตารางอัตราแลกเปลี่ยน

## สำหรับเว็บที่ไม่ใช้ Elementor (เช่น siamfinancial.com)

---

## 🎯 Shortcode ที่ใช้ได้

### 1. **Ticker Banner** (แบบเลื่อน)
```
[tfd_ticker_banner height="44px" items="exchange,gold,oil" speed="50" bg_color="#1a5f4a" text_color="#ffffff" show_flags="yes"]
```

### 2. **ตารางอัตราแลกเปลี่ยน** (แบบเต็ม - เหมือน blogeverydayth)
```
[tfd_exchange_table]
```

---

## 📋 ตารางอัตราแลกเปลี่ยน - รายละเอียด

### แบบพื้นฐาน
```
[tfd_exchange_table]
```
แสดงสกุลเงินทั้งหมด: USD, EUR, GBP, JPY, CNY, HKD, SGD, MYR, AUD, NZD, CAD, CHF, SEK, NOK, DKK, INR, IDR, PHP

### เลือกสกุลเงินที่จะแสดง
```
[tfd_exchange_table currencies="USD,EUR,GBP,JPY,CNY"]
```
แสดงเฉพาะ 5 สกุลเงินหลัก

### ปิดธงชาติ
```
[tfd_exchange_table show_flags="no"]
```

### ปิดคอลัมน์เปลี่ยนแปลง
```
[tfd_exchange_table show_change="no"]
```

### เปลี่ยนสไตล์ตาราง
```
[tfd_exchange_table table_style="modern"]
```
- `modern` = สไตล์ทันสมัย (ค่าเริ่มต้น)
- `classic` = สไตล์คลาสสิก (มีเส้นขอบ)
- `minimal` = สไตล์มินิมอล (เรียบง่าย)

### ตัวอย่างแบบเต็ม
```
[tfd_exchange_table currencies="USD,EUR,GBP,JPY,CNY,HKD,SGD,MYR" show_flags="yes" show_change="yes" table_style="modern"]
```

---

## 🎨 ตัวอย่างการใช้งานในหน้า WordPress

### หน้าอัตราแลกเปลี่ยน (เหมือน blogeverydayth)

1. สร้างหน้าใหม่: **Pages → Add New**
2. ตั้งชื่อ: "อัตราแลกเปลี่ยนวันนี้"
3. เพิ่ม **Shortcode Block**
4. วางโค้ด:

```
[tfd_ticker_banner height="44px" items="exchange" speed="50" bg_color="#667eea" text_color="#ffffff"]

[tfd_exchange_table currencies="USD,EUR,GBP,JPY,CNY,HKD,SGD,MYR,AUD,NZD,CAD,CHF,SEK,NOK,DKK,INR,IDR,PHP" show_flags="yes" show_change="yes" table_style="modern"]
```

5. **Publish**

---

## 🔄 การใส่ข้อมูล

### วิธีที่ 1: ผ่าน Browser Console (ง่ายที่สุด)

1. เปิด WordPress Admin
2. กด **F12** → แท็บ **Console**
3. วางโค้ดนี้:

```javascript
fetch('/wp-json/tfd/v1/update-data', {
    method: 'POST',
    headers: {
        'Content-Type': 'application/json',
        'X-WP-Nonce': wpApiSettings.nonce
    },
    body: JSON.stringify({
        exchange_rates: {
            'USD': { buy: '35.50', sell: '35.80', change: 0.15 },
            'EUR': { buy: '38.20', sell: '38.50', change: -0.08 },
            'GBP': { buy: '43.50', sell: '43.90', change: 0.22 },
            'JPY': { buy: '0.24', sell: '0.25', change: -0.05 },
            'CNY': { buy: '4.90', sell: '5.10', change: 0.10 },
            'HKD': { buy: '4.50', sell: '4.70', change: 0.03 },
            'SGD': { buy: '26.30', sell: '26.60', change: 0.12 },
            'MYR': { buy: '7.65', sell: '7.90', change: -0.02 },
            'AUD': { buy: '20.72', sell: '21.52', change: 0.18 },
            'NZD': { buy: '18.04', sell: '18.64', change: 0.09 },
            'CAD': { buy: '22.80', sell: '23.27', change: 0.14 },
            'CHF': { buy: '39.65', sell: '40.33', change: -0.11 },
            'SEK': { buy: '3.39', sell: '3.47', change: 0.06 },
            'NOK': { buy: '3.10', sell: '3.17', change: -0.04 },
            'DKK': { buy: '4.93', sell: '5.01', change: 0.08 },
            'INR': { buy: '0.31', sell: '0.38', change: 0.02 },
            'IDR': { buy: '1.79', sell: '1.98', change: -0.01 },
            'PHP': { buy: '0.51', sell: '0.55', change: 0.05 }
        },
        gold_prices: {
            bar_buy: '28500.00',
            bar_sell: '28600.00',
            ornament_buy: '27800.00',
            ornament_sell: '29200.00'
        },
        oil_prices: {
            gasohol_91: { today: '35.50', tomorrow: '35.80' },
            gasohol_95: { today: '37.20', tomorrow: '37.50' },
            diesel: { today: '32.00', tomorrow: '32.20' },
            diesel_b7: { today: '31.80', tomorrow: '32.00' }
        }
    })
})
.then(response => response.json())
.then(data => {
    console.log('✅ สำเร็จ!', data);
    alert('✅ ใส่ข้อมูลสำเร็จ! Refresh หน้าเว็บ');
})
.catch(error => console.error('❌ ผิดพลาด:', error));
```

4. กด **Enter**
5. **Refresh หน้าเว็บ**

---

### วิธีที่ 2: ผ่าน Make.com Webhook (แนะนำสำหรับใช้งานจริง)

ตั้งค่า Make.com ให้ส่งข้อมูลมาที่:
```
POST https://siamfinancial.com/wp-json/tfd/v1/update-data
```

ตัวอย่าง JSON:
```json
{
  "exchange_rates": {
    "USD": { "buy": "35.50", "sell": "35.80", "change": 0.15 },
    "EUR": { "buy": "38.20", "sell": "38.50", "change": -0.08 }
  },
  "gold_prices": {
    "bar_buy": "28500.00",
    "bar_sell": "28600.00"
  },
  "oil_prices": {
    "gasohol_91": { "today": "35.50", "tomorrow": "35.80" }
  }
}
```

---

## 📦 การติดตั้ง Plugin เวอร์ชันใหม่

1. ลบ plugin เก่า: **Plugins → Deactivate → Delete**
2. ติดตั้งใหม่: **Plugins → Add New → Upload**
3. เลือก `thailand-financial-data-plugin-v2.zip` จาก Desktop
4. **Install Now → Activate**

---

## 🆚 เปรียบเทียบ blogeverydayth vs siamfinancial

| ฟีเจอร์ | blogeverydayth | siamfinancial |
|---------|----------------|---------------|
| Editor | Elementor | WordPress Block Editor |
| ใส่ข้อมูล | Dynamic Tags | Shortcode |
| ตาราง | Elementor Table Widget | `[tfd_exchange_table]` |
| Ticker | Elementor Widget | `[tfd_ticker_banner]` |
| ธงชาติ | ✅ | ✅ |
| เปลี่ยนแปลง | ✅ | ✅ |

---

## 💡 Tips

- ใช้ `[tfd_ticker_banner]` ใน Header เพื่อแสดงทุกหน้า
- ใช้ `[tfd_exchange_table]` ในหน้าเฉพาะ
- ตั้งค่า Make.com ให้อัปเดทข้อมูลทุก 1 ชั่วโมง
- เพิ่ม `change` (เปอร์เซ็นต์เปลี่ยนแปลง) เพื่อแสดงลูกศร ▲▼

---

## 🐛 Troubleshooting

### ไม่แสดงข้อมูล?
- ตรวจสอบว่าใส่ข้อมูลแล้วหรือยัง (ใช้ Console วิธีที่ 1)
- Refresh หน้าเว็บ (Ctrl+F5)

### ตารางไม่สวย?
- ตรวจสอบว่า CSS โหลดแล้ว
- ลองเปลี่ยน `table_style="classic"` หรือ `"minimal"`

---

**ไฟล์บน Desktop:**
- `thailand-financial-data-plugin-v2.zip` - Plugin เวอร์ชันใหม่ (มีตาราง + ticker)

**ติดตั้งแล้วใช้ shortcode นี้ได้เลย!** 🎉
