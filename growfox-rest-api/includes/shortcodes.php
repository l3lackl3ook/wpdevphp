<?php
/**
 * Shortcodes สำหรับ Growfox REST API
 * ใช้กับเว็บที่ไม่มี Elementor (เช่น siamfinancial.com)
 */

if (!defined('ABSPATH')) exit;

class Growfox_Shortcodes {
    private static $instance = null;
    
    public static function instance() {
        if (is_null(self::$instance)) {
            self::$instance = new self();
        }
        return self::$instance;
    }
    
    public function __construct() {
        add_shortcode('growfox_ticker', [$this, 'ticker_banner']);
        add_shortcode('growfox_table', [$this, 'exchange_table']);
        add_shortcode('growfox_rate', [$this, 'single_rate']);
        add_shortcode('growfox_gold', [$this, 'gold_price_card']);
        add_shortcode('growfox_oil', [$this, 'oil_price_table']);
        
        add_action('wp_enqueue_scripts', [$this, 'enqueue_assets']);
    }
    
    public function enqueue_assets() {
        wp_enqueue_style('growfox-shortcodes', plugin_dir_url(dirname(__FILE__)) . 'assets/css/shortcodes.css', [], GROWFOX_VERSION, 'all');
        wp_enqueue_script('growfox-shortcodes', plugin_dir_url(dirname(__FILE__)) . 'assets/js/shortcodes.js', ['jquery'], GROWFOX_VERSION, true);
        
        // เพิ่ม inline CSS เพื่อ override theme
        $custom_css = "
            .col-4.the-column.gold * { box-sizing: border-box; }
            .col-4.the-column.gold p { margin: 0 !important; padding: 0 !important; }
            .col-4.the-column.gold .g-price { 
                font-size: 36px !important; 
                color: #f39c12 !important; 
                font-weight: 700 !important;
                line-height: 1.2 !important;
            }
        ";
        wp_add_inline_style('growfox-shortcodes', $custom_css);
    }
    
    /**
     * Shortcode: [growfox_ticker]
     * Ticker banner เลื่อนแสดงอัตราแลกเปลี่ยน
     */
    public function ticker_banner($atts) {
        $atts = shortcode_atts([
            'currencies' => 'USD,EUR,GBP,JPY,CNY,HKD,SGD', // สกุลเงินที่จะแสดง
            'height' => '44px',
            'speed' => '50',
            'bg_color' => '#1a5f4a',
            'text_color' => '#ffffff',
            'show_flags' => 'yes',
        ], $atts);
        
        $data = get_option('options_exchange_data');
        if (!$data) {
            return '<div class="growfox-no-data">ไม่มีข้อมูล - กรุณาตั้งค่า Make.com webhook</div>';
        }
        
        $rates = json_decode($data, true);
        if (!$rates) {
            return '<div class="growfox-no-data">ข้อมูลไม่ถูกต้อง</div>';
        }
        
        $currencies_array = array_map('trim', explode(',', strtoupper($atts['currencies'])));
        
        // ข้อมูลธงชาติ
        $flags = [
            'USD' => '🇺🇸', 'EUR' => '🇪🇺', 'GBP' => '🇬🇧', 'JPY' => '🇯🇵',
            'CNY' => '🇨🇳', 'HKD' => '🇭🇰', 'SGD' => '🇸🇬', 'MYR' => '🇲🇾',
            'AUD' => '🇦🇺', 'NZD' => '🇳🇿', 'CAD' => '🇨🇦', 'CHF' => '🇨🇭',
            'SEK' => '🇸🇪', 'NOK' => '🇳🇴', 'DKK' => '🇩🇰', 'INR' => '🇮🇳',
            'IDR' => '🇮🇩', 'PHP' => '🇵🇭', 'KRW' => '🇰🇷', 'TWD' => '🇹🇼',
        ];
        
        $ticker_items = [];
        foreach ($rates as $rate) {
            $currency = strtoupper($rate['currency_id'] ?? '');
            if (in_array($currency, $currencies_array)) {
                // ใช้ buying_sight (ซื้อ) แทน buying_transfer
                $buy = $rate['buying_sight'] ?? $rate['buying'] ?? '-';
                $sell = $rate['selling'] ?? '-';
                
                // คำนวณ spread (ความแตกต่าง) เพื่อแสดงสี
                $change_class = '';
                $change_icon = '';
                if ($buy !== '-' && $sell !== '-') {
                    $spread = (float)$sell - (float)$buy;
                    if ($spread > 0.5) {
                        $change_class = 'positive';
                        $change_icon = '▲';
                    } elseif ($spread < 0.3) {
                        $change_class = 'negative';
                        $change_icon = '▼';
                    } else {
                        $change_class = 'neutral';
                        $change_icon = '';
                    }
                }
                
                $ticker_items[] = [
                    'currency' => $currency,
                    'flag' => $flags[$currency] ?? '',
                    'buy' => $buy,
                    'sell' => $sell,
                    'change_class' => $change_class,
                    'change_icon' => $change_icon,
                ];
            }
        }
        
        if (empty($ticker_items)) {
            return '<div class="growfox-no-data">ไม่พบสกุลเงินที่ระบุ</div>';
        }
        
        ob_start();
        ?>
        <div class="growfox-ticker-wrapper" style="height: <?php echo esc_attr($atts['height']); ?>; background-color: <?php echo esc_attr($atts['bg_color']); ?>; color: <?php echo esc_attr($atts['text_color']); ?>;">
            <div class="growfox-ticker" data-speed="<?php echo esc_attr($atts['speed']); ?>">
                <div class="growfox-ticker-content">
                    <?php foreach ($ticker_items as $item): ?>
                        <div class="growfox-ticker-item">
                            <?php if ($atts['show_flags'] === 'yes'): ?>
                                <span class="growfox-flag"><?php echo $item['flag']; ?></span>
                            <?php endif; ?>
                            <span class="growfox-currency"><?php echo esc_html($item['currency']); ?></span>
                            <?php if (!empty($item['change_icon'])): ?>
                                <span class="growfox-change-icon <?php echo $item['change_class']; ?>"><?php echo $item['change_icon']; ?></span>
                            <?php endif; ?>
                            <span class="growfox-label">ซื้อ:</span>
                            <span class="growfox-value <?php echo $item['change_class']; ?>"><?php echo esc_html(number_format((float)$item['buy'], 4)); ?></span>
                            <span class="growfox-separator">|</span>
                            <span class="growfox-label">ขาย:</span>
                            <span class="growfox-value <?php echo $item['change_class']; ?>"><?php echo esc_html(number_format((float)$item['sell'], 4)); ?></span>
                        </div>
                    <?php endforeach; ?>
                </div>
                <!-- Duplicate for seamless loop -->
                <div class="growfox-ticker-content" aria-hidden="true">
                    <?php foreach ($ticker_items as $item): ?>
                        <div class="growfox-ticker-item">
                            <?php if ($atts['show_flags'] === 'yes'): ?>
                                <span class="growfox-flag"><?php echo $item['flag']; ?></span>
                            <?php endif; ?>
                            <span class="growfox-currency"><?php echo esc_html($item['currency']); ?></span>
                            <?php if (!empty($item['change_icon'])): ?>
                                <span class="growfox-change-icon <?php echo $item['change_class']; ?>"><?php echo $item['change_icon']; ?></span>
                            <?php endif; ?>
                            <span class="growfox-label">ซื้อ:</span>
                            <span class="growfox-value <?php echo $item['change_class']; ?>"><?php echo esc_html(number_format((float)$item['buy'], 4)); ?></span>
                            <span class="growfox-separator">|</span>
                            <span class="growfox-label">ขาย:</span>
                            <span class="growfox-value <?php echo $item['change_class']; ?>"><?php echo esc_html(number_format((float)$item['sell'], 4)); ?></span>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
        <?php
        return ob_get_clean();
    }
    
    /**
     * Shortcode: [growfox_table]
     * ตารางอัตราแลกเปลี่ยนแบบเต็ม
     */
    public function exchange_table($atts) {
        $atts = shortcode_atts([
            'currencies' => 'USD,EUR,GBP,JPY,CNY,HKD,SGD,MYR,AUD,NZD,CAD,CHF,SEK,NOK,DKK,INR,IDR,PHP',
            'show_flags' => 'yes',
            'table_style' => 'modern',
        ], $atts);
        
        $data = get_option('options_exchange_data');
        $period = get_option('options_period');
        $last_update = get_option('options_last_update');
        
        if (!$data) {
            return '<div class="growfox-no-data">ไม่มีข้อมูล - กรุณาตั้งค่า Make.com webhook</div>';
        }
        
        $rates = json_decode($data, true);
        if (!$rates) {
            return '<div class="growfox-no-data">ข้อมูลไม่ถูกต้อง</div>';
        }
        
        $currencies_array = array_map('trim', explode(',', strtoupper($atts['currencies'])));
        
        // ข้อมูลธงชาติและชื่อเต็ม
        $currency_info = [
            'USD' => ['flag' => '🇺🇸', 'name' => 'สหรัฐอเมริกา - ดอลลาร์สหรัฐ (USD)'],
            'EUR' => ['flag' => '🇪🇺', 'name' => 'ยูโรโซน - ยูโร (EUR)'],
            'GBP' => ['flag' => '🇬🇧', 'name' => 'อังกฤษ - ปอนด์สเตอร์ลิง (GBP)'],
            'JPY' => ['flag' => '🇯🇵', 'name' => 'ญี่ปุ่น - เยน (100 เยน) (JPY)'],
            'CNY' => ['flag' => '🇨🇳', 'name' => 'จีน - หยวน (CNY)'],
            'HKD' => ['flag' => '🇭🇰', 'name' => 'ฮ่องกง - ดอลลาร์ฮ่องกง (HKD)'],
            'SGD' => ['flag' => '🇸🇬', 'name' => 'สิงคโปร์ - ดอลลาร์สิงคโปร์ (SGD)'],
            'MYR' => ['flag' => '🇲🇾', 'name' => 'มาเลเซีย - ริงกิต (MYR)'],
            'AUD' => ['flag' => '🇦🇺', 'name' => 'ออสเตรเลีย - ดอลลาร์ออสเตรเลีย (AUD)'],
            'NZD' => ['flag' => '🇳🇿', 'name' => 'นิวซีแลนด์ - ดอลลาร์นิวซีแลนด์ (NZD)'],
            'CAD' => ['flag' => '🇨🇦', 'name' => 'แคนาดา - ดอลลาร์แคนาดา (CAD)'],
            'CHF' => ['flag' => '🇨🇭', 'name' => 'สวิตเซอร์แลนด์ - ฟรังก์สวิส (CHF)'],
            'SEK' => ['flag' => '🇸🇪', 'name' => 'สวีเดน - โครนา (SEK)'],
            'NOK' => ['flag' => '🇳🇴', 'name' => 'นอร์เวย์ - โครนา (NOK)'],
            'DKK' => ['flag' => '🇩🇰', 'name' => 'เดนมาร์ก - โครนา (DKK)'],
            'INR' => ['flag' => '🇮🇳', 'name' => 'อินเดีย - รูปี (INR)'],
            'IDR' => ['flag' => '🇮🇩', 'name' => 'อินโดนีเซีย - รูเปียห์ (1,000 รูเปียห์) (IDR)'],
            'PHP' => ['flag' => '🇵🇭', 'name' => 'ฟิลิปปินส์ - เปโซ (PHP)'],
        ];
        
        ob_start();
        ?>
        <div class="growfox-table-wrapper growfox-table-<?php echo esc_attr($atts['table_style']); ?>">
            <?php if ($period || $last_update): ?>
                <div class="growfox-table-header">
                    <?php if ($period): ?>
                        <p class="growfox-period">ประจำวันที่ <?php echo esc_html($period); ?></p>
                    <?php endif; ?>
                    <?php if ($last_update): ?>
                        <p class="growfox-last-update">อัพเดทล่าสุด: <?php echo esc_html(date('d/m/Y H:i', strtotime($last_update))); ?> น.</p>
                    <?php endif; ?>
                </div>
            <?php endif; ?>
            
            <div class="growfox-table-responsive">
                <table class="growfox-exchange-table">
                    <thead>
                        <tr>
                            <th class="growfox-col-currency">สกุลเงิน</th>
                            <th class="growfox-col-buy">ซื้อเข้า</th>
                            <th class="growfox-col-sell">ขายออก</th>
                            <th class="growfox-col-transfer">โอนเข้า</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($rates as $rate): ?>
                            <?php 
                            $currency = strtoupper($rate['currency_id'] ?? '');
                            if (!in_array($currency, $currencies_array)) continue;
                            
                            $info = $currency_info[$currency] ?? ['flag' => '', 'name' => $currency];
                            ?>
                            <tr class="growfox-currency-row">
                                <td class="growfox-col-currency">
                                    <?php if ($atts['show_flags'] === 'yes'): ?>
                                        <span class="growfox-flag"><?php echo $info['flag']; ?></span>
                                    <?php endif; ?>
                                    <span class="growfox-currency-name"><?php echo esc_html($info['name']); ?></span>
                                </td>
                                <td class="growfox-col-buy">
                                    <?php 
                                    $buy = $rate['buying_sight'] ?? $rate['buying'] ?? null;
                                    echo $buy ? esc_html(number_format((float)$buy, 4)) : '-'; 
                                    ?>
                                </td>
                                <td class="growfox-col-sell">
                                    <?php echo isset($rate['selling']) ? esc_html(number_format((float)$rate['selling'], 4)) : '-'; ?>
                                </td>
                                <td class="growfox-col-transfer">
                                    <?php 
                                    $transfer = $rate['buying_transfer'] ?? $rate['transfer'] ?? null;
                                    echo $transfer ? esc_html(number_format((float)$transfer, 4)) : '-'; 
                                    ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
            
            <div class="growfox-table-footer">
                <p class="growfox-disclaimer">
                    <small>ข้อมูลอ้างอิงจากธนาคารแห่งประเทศไทย อัตราแลกเปลี่ยนอาจมีการเปลี่ยนแปลงตามสภาวะตลาด</small>
                </p>
            </div>
        </div>
        <?php
        return ob_get_clean();
    }
    
    /**
     * Shortcode: [growfox_rate currency="USD" type="selling"]
     * แสดงอัตราแลกเปลี่ยนเดี่ยว
     */
    public function single_rate($atts) {
        $atts = shortcode_atts([
            'currency' => 'USD',
            'type' => 'selling', // buying, selling, transfer
            'format' => 'number', // number, text
        ], $atts);
        
        $data = get_option('options_exchange_data');
        if (!$data) return '-';
        
        $rates = json_decode($data, true);
        if (!$rates) return '-';
        
        $currency = strtoupper($atts['currency']);
        foreach ($rates as $rate) {
            if (strtoupper($rate['currency_id'] ?? '') === $currency) {
                $value = $rate[$atts['type']] ?? null;
                if ($value) {
                    return $atts['format'] === 'number' ? number_format((float)$value, 4) : $value;
                }
            }
        }
        
        return '-';
    }
    
    /**
     * Shortcode: [growfox_gold]
     * แสดง Card ราคาทองคำ
     */
    public function gold_price_card($atts) {
        $atts = shortcode_atts([
            'style' => 'card',
        ], $atts);
        
        $data = get_option('options_gold_data');
        $last_update = get_option('options_gold_last_update');
        
        if (!$data) {
            return '<div class="growfox-no-data">ไม่มีข้อมูลราคาทอง - กรุณาตั้งค่า Make.com webhook</div>';
        }
        
        $gold = json_decode($data, true);
        if (!$gold) {
            return '<div class="growfox-no-data">ข้อมูลไม่ถูกต้อง</div>';
        }
        
        // ดึงข้อมูล และลบ comma ออกก่อนแปลงเป็นตัวเลข
        $bar_buy = $gold['bar_buy'] ?? $gold['buy'] ?? '68100';
        $bar_buy = str_replace(',', '', $bar_buy); // ลบ comma
        
        $bar_sell = $gold['bar_sell'] ?? $gold['sell'] ?? '68200';
        $bar_sell = str_replace(',', '', $bar_sell); // ลบ comma
        
        $change = $gold['change'] ?? $gold['diff'] ?? 500;
        $change = str_replace(',', '', $change); // ลบ comma
        
        $date = $gold['date'] ?? date('d/m/Y');
        $time = $gold['time'] ?? date('H:i');
        
        // คำนวณสี
        $change_class = 'neutral';
        $change_sign = '';
        if ($change > 0) {
            $change_class = 'plus';
            $change_sign = '+';
        } elseif ($change < 0) {
            $change_class = 'minus';
            $change_sign = '';
        }
        
        ob_start();
        ?>
        <div class="col-4 the-column gold" style="background: #fff; border-radius: 12px; box-shadow: 0 4px 20px rgba(0,0,0,0.1); padding: 30px; margin: 30px auto; max-width: 600px;">
            <div class="title-wrapper" style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 25px; padding-bottom: 20px; border-bottom: 2px solid #f0f0f0;">
                <h2 class="exc-title" style="font-size: 24px; font-weight: 700; color: #2c3e50; margin: 0;">ราคาทองคำวันนี้</h2>
                <img src="https://assets.aommoney.com/aommoney/wp-content/uploads/2025/03/gold-img.png" alt="ราคาทองคำวันนี้" class="gold-img" style="width: 60px; height: auto;">
            </div>
            <div id="home-gold-price">
                <div class="gold-label" style="font-size: 18px; font-weight: 600; color: #7f8c8d; margin-bottom: 20px; text-align: center;">ราคาทองคำแท่ง</div>
                <div class="gold-row" style="display: grid; grid-template-columns: repeat(2, 1fr); gap: 20px; background: linear-gradient(135deg, #fff9e6 0%, #fff5d6 100%); padding: 25px; border-radius: 12px; margin-bottom: 20px;">
                    <div class="price" style="text-align: center;">
                        <p class="tag-title" style="font-size: 14px; color: #7f8c8d; margin: 0 0 10px 0 !important; font-weight: 500;">รับซื้อ (บาทละ)</p>
                        <p class="g-price" style="font-size: 36px !important; font-weight: 700 !important; color: #f39c12 !important; margin: 0 !important; text-shadow: 0 2px 4px rgba(0,0,0,0.1);"><?php echo number_format((float)$bar_buy, 0); ?></p>
                    </div>
                    <div class="price" style="text-align: center;">
                        <p class="tag-title" style="font-size: 14px; color: #7f8c8d; margin: 0 0 10px 0 !important; font-weight: 500;">ขายออก (บาทละ)</p>
                        <p class="g-price" style="font-size: 36px !important; font-weight: 700 !important; color: #f39c12 !important; margin: 0 !important; text-shadow: 0 2px 4px rgba(0,0,0,0.1);"><?php echo number_format((float)$bar_sell, 0); ?></p>
                    </div>
                    <div class="date" style="text-align: center; font-size: 14px; color: #7f8c8d;">
                        <p style="margin: 0 !important; line-height: 1.6;">ประจำวันที่<br><?php echo esc_html($date); ?></p>
                    </div>
                    <div class="time" style="text-align: center; font-size: 14px; color: #7f8c8d;">
                        <p style="margin: 0 !important; line-height: 1.6;">เวลา<br><?php echo esc_html($time); ?></p>
                    </div>
                    <div class="diff <?php echo $change_class; ?>" style="text-align: center; padding: 15px; border-radius: 8px; grid-column: span 2; background: <?php echo $change_class === 'plus' ? 'linear-gradient(135deg, #d4edda 0%, #c3e6cb 100%)' : ($change_class === 'minus' ? 'linear-gradient(135deg, #f8d7da 0%, #f5c6cb 100%)' : 'linear-gradient(135deg, #e2e3e5 0%, #d6d8db 100%)'); ?>;">
                        <p class="txt" style="font-size: 14px; color: #7f8c8d; margin: 0 0 5px 0 !important; font-weight: 500;">วันนี้</p>
                        <p class="num" style="font-size: 28px; font-weight: 700; margin: 0 !important; color: <?php echo $change_class === 'plus' ? '#27ae60' : ($change_class === 'minus' ? '#e74c3c' : '#7f8c8d'); ?>;"><?php echo $change_sign . number_format((float)$change, 0); ?></p>
                    </div>
                </div>
                <div class="ref-wrapper" style="text-align: center; font-size: 13px; color: #95a5a6; margin-top: 15px;">ข้อมูลจากสมาคมค้าทองคำ</div>
            </div>
        </div>
        <?php
        return ob_get_clean();
    }
    
    /**
     * Shortcode: [growfox_oil]
     * แสดงตารางราคาน้ำมัน
     */
    public function oil_price_table($atts) {
        $atts = shortcode_atts([
            'style' => 'table',
        ], $atts);
        
        $data = get_option('options_oil_data');
        $last_update = get_option('options_oil_last_update');
        
        if (!$data) {
            return '<div class="growfox-no-data">ไม่มีข้อมูลราคาน้ำมัน - กรุณาตั้งค่า Make.com webhook</div>';
        }
        
        $oil = json_decode($data, true);
        if (!$oil || !isset($oil['products'])) {
            return '<div class="growfox-no-data">ข้อมูลไม่ถูกต้อง</div>';
        }
        
        $products = $oil['products'];
        $date = $oil['date'] ?? date('d/m/Y');
        
        ob_start();
        ?>
        <div class="col-4 the-column oil-price">
            <div class="title-wrapper">
                <h2 class="exc-title">ราคาน้ำมัน</h2>
                <img src="https://oil-price.bangchak.co.th/icon/logo_bcp.svg" 
                     alt="ราคาน้ำมัน" 
                     class="oil-logo">
            </div>
            <div id="home-oil-price">
                <ul>
                    <li class="title">
                        <div class="cl-1">ชนิดน้ำมัน (บาท/ลิตร)</div>
                        <div class="cl-2">วันนี้</div>
                        <div class="cl-3">พรุ่งนี้</div>
                    </li>
                    <?php foreach ($products as $product): ?>
                        <li class="listing">
                            <div class="cl-1">
                                <img src="<?php echo esc_url($product['image']); ?>" 
                                     alt="<?php echo esc_attr($product['name']); ?>">
                            </div>
                            <div class="cl-2"><?php echo esc_html(number_format((float)$product['price_today'], 2)); ?></div>
                            <div class="cl-3"><?php echo esc_html(number_format((float)$product['price_tomorrow'], 2)); ?></div>
                        </li>
                    <?php endforeach; ?>
                    <div class="ref-wrapper">
                        ข้อมูลจากบริษัท บางจาก คอร์ปอเรชั่น จำกัด (มหาชน) วันที่ <?php echo esc_html($date); ?>
                    </div>
                </ul>
            </div>
        </div>
        <?php
        return ob_get_clean();
    }
}

// Initialize
Growfox_Shortcodes::instance();
