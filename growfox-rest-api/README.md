# Growfox Rest API

รับข้อมูลอัตราแลกเปลี่ยนจาก Make.com และเก็บใน ACF Fields

## API Endpoint

```
POST https://yourdomain.com/wp-json/growfox/v1/exchange-rates
```

## ตัวอย่าง JSON จาก Make.com

```json
{
  "usd_buy": "34.50",
  "usd_sell": "35.20",
  "eur_buy": "38.10",
  "eur_sell": "39.50"
}
```

## วิธีใช้งาน

1. ติดตั้ง Plugin
2. ตั้งค่า Make.com ให้ส่งข้อมูลมาที่ API Endpoint
3. ใน Elementor ใช้ Dynamic Tag "Growfox → อัตราแลกเปลี่ยน"
