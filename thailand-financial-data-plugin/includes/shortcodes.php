<?php
/**
 * Shortcodes
 * สำหรับแสดงข้อมูลในหน้าเว็บ
 */

if (!defined('ABSPATH')) {
    exit;
}

class TFD_Shortcodes {
    
    private static $instance = null;
    
    public static function get_instance() {
        if (null === self::$instance) {
            self::$instance = new self();
        }
        return self::$instance;
    }
    
    private function __construct() {
        add_shortcode('tfd_exchange_rate', array($this, 'exchange_rate_shortcode'));
        add_shortcode('tfd_oil_price', array($this, 'oil_price_shortcode'));
        add_shortcode('tfd_gold_price', array($this, 'gold_price_shortcode'));
        add_shortcode('tfd_ticker_banner', array($this, 'ticker_banner_shortcode'));
        add_shortcode('tfd_exchange_table', array($this, 'exchange_table_shortcode'));
        
        // Enqueue styles and scripts
        add_action('wp_enqueue_scripts', array($this, 'enqueue_assets'));
    }
    
    /**
     * Enqueue CSS and JS for ticker banner
     */
    public function enqueue_assets() {
        wp_enqueue_style('tfd-ticker-banner', TFD_PLUGIN_URL . 'assets/css/ticker-banner.css', array(), TFD_VERSION);
        wp_enqueue_script('tfd-ticker-banner', TFD_PLUGIN_URL . 'assets/js/ticker-banner.js', array('jquery'), TFD_VERSION, true);
    }
    
    /**
     * Shortcode: [tfd_exchange_rate currency="USD" type="buy"]
     */
    public function exchange_rate_shortcode($atts) {
        $atts = shortcode_atts(array(
            'currency' => 'USD',
            'type' => 'buy',
            'fallback' => '-',
        ), $atts);
        
        $exchange_rates = get_option('tfd_exchange_rates', array());
        
        if (isset($exchange_rates[$atts['currency']][$atts['type']])) {
            return esc_html($exchange_rates[$atts['currency']][$atts['type']]);
        }
        
        return esc_html($atts['fallback']);
    }
    
    /**
     * Shortcode: [tfd_oil_price type="gasohol_91" day="today"]
     */
    public function oil_price_shortcode($atts) {
        $atts = shortcode_atts(array(
            'type' => 'gasohol_91',
            'day' => 'today',
            'fallback' => '-',
        ), $atts);
        
        $oil_prices = get_option('tfd_oil_prices', array());
        
        if (isset($oil_prices[$atts['type']][$atts['day']])) {
            return esc_html($oil_prices[$atts['type']][$atts['day']]);
        }
        
        return esc_html($atts['fallback']);
    }
    
    /**
     * Shortcode: [tfd_gold_price type="bar_buy"]
     */
    public function gold_price_shortcode($atts) {
        $atts = shortcode_atts(array(
            'type' => 'bar_buy',
            'fallback' => '-',
        ), $atts);
        
        $gold_prices = get_option('tfd_gold_prices', array());
        
        if (isset($gold_prices[$atts['type']])) {
            return esc_html($gold_prices[$atts['type']]);
        }
        
        return esc_html($atts['fallback']);
    }
    
    /**
     * Shortcode: [tfd_ticker_banner height="44px" items="exchange,gold,oil"]
     * แสดง ticker banner พร้อมธงชาติ
     */
    public function ticker_banner_shortcode($atts) {
        $atts = shortcode_atts(array(
            'height' => '44px',
            'items' => 'exchange,gold,oil',
            'speed' => '50',
            'bg_color' => '#1a5f4a',
            'text_color' => '#ffffff',
            'show_flags' => 'yes', // แสดงธงชาติ
        ), $atts);
        
        $exchange_rates = get_option('tfd_exchange_rates', array());
        $gold_prices = get_option('tfd_gold_prices', array());
        $oil_prices = get_option('tfd_oil_prices', array());
        
        $items_array = array_map('trim', explode(',', $atts['items']));
        
        // ข้อมูลธงชาติและชื่อเต็ม
        $currency_info = array(
            'USD' => array('flag' => '🇺🇸', 'name' => 'ดอลลาร์สหรัฐ'),
            'EUR' => array('flag' => '🇪🇺', 'name' => 'ยูโร'),
            'GBP' => array('flag' => '🇬🇧', 'name' => 'ปอนด์อังกฤษ'),
            'JPY' => array('flag' => '🇯🇵', 'name' => 'เยนญี่ปุ่น'),
            'CNY' => array('flag' => '🇨🇳', 'name' => 'หยวนจีน'),
            'HKD' => array('flag' => '🇭🇰', 'name' => 'ดอลลาร์ฮ่องกง'),
            'SGD' => array('flag' => '🇸🇬', 'name' => 'ดอลลาร์สิงคโปร์'),
            'MYR' => array('flag' => '🇲🇾', 'name' => 'ริงกิตมาเลเซีย'),
            'AUD' => array('flag' => '🇦🇺', 'name' => 'ดอลลาร์ออสเตรเลีย'),
            'NZD' => array('flag' => '🇳🇿', 'name' => 'ดอลลาร์นิวซีแลนด์'),
            'CAD' => array('flag' => '🇨🇦', 'name' => 'ดอลลาร์แคนาดา'),
            'CHF' => array('flag' => '🇨🇭', 'name' => 'ฟรังก์สวิส'),
            'SEK' => array('flag' => '🇸🇪', 'name' => 'โครนาสวีเดน'),
            'NOK' => array('flag' => '🇳🇴', 'name' => 'โครนานอร์เวย์'),
            'DKK' => array('flag' => '🇩🇰', 'name' => 'โครนาเดนมาร์ก'),
            'INR' => array('flag' => '🇮🇳', 'name' => 'รูปีอินเดีย'),
            'IDR' => array('flag' => '🇮🇩', 'name' => 'รูเปียห์อินโดนีเซีย'),
            'PHP' => array('flag' => '🇵🇭', 'name' => 'เปโซฟิลิปปินส์'),
            'KRW' => array('flag' => '🇰🇷', 'name' => 'วอนเกาหลี'),
            'TWD' => array('flag' => '🇹🇼', 'name' => 'ดอลลาร์ไต้หวัน'),
        );
        
        $ticker_items = array();
        
        // เพิ่มข้อมูลอัตราแลกเปลี่ยน
        if (in_array('exchange', $items_array) && !empty($exchange_rates)) {
            foreach ($exchange_rates as $currency => $rates) {
                if (isset($rates['buy'])) {
                    $flag = isset($currency_info[$currency]) ? $currency_info[$currency]['flag'] : '';
                    $name = isset($currency_info[$currency]) ? $currency_info[$currency]['name'] : $currency;
                    
                    $ticker_items[] = array(
                        'flag' => $flag,
                        'label' => $currency,
                        'name' => $name,
                        'value' => number_format((float)$rates['buy'], 4),
                        'type' => 'exchange',
                        'change' => isset($rates['change']) ? $rates['change'] : null
                    );
                }
            }
        }
        
        // เพิ่มข้อมูลราคาทอง
        if (in_array('gold', $items_array) && !empty($gold_prices)) {
            if (isset($gold_prices['bar_buy'])) {
                $ticker_items[] = array(
                    'flag' => '🏆',
                    'label' => 'ทองคำแท่ง',
                    'name' => 'ซื้อ',
                    'value' => number_format((float)$gold_prices['bar_buy'], 2),
                    'type' => 'gold',
                    'change' => null
                );
            }
            if (isset($gold_prices['bar_sell'])) {
                $ticker_items[] = array(
                    'flag' => '🏆',
                    'label' => 'ทองคำแท่ง',
                    'name' => 'ขาย',
                    'value' => number_format((float)$gold_prices['bar_sell'], 2),
                    'type' => 'gold',
                    'change' => null
                );
            }
        }
        
        // เพิ่มข้อมูลราคาน้ำมัน
        if (in_array('oil', $items_array) && !empty($oil_prices)) {
            if (isset($oil_prices['gasohol_91']['today'])) {
                $ticker_items[] = array(
                    'flag' => '⛽',
                    'label' => 'แก๊สโซฮอล์ 91',
                    'name' => '',
                    'value' => number_format((float)$oil_prices['gasohol_91']['today'], 2),
                    'type' => 'oil',
                    'change' => null
                );
            }
            if (isset($oil_prices['diesel']['today'])) {
                $ticker_items[] = array(
                    'flag' => '⛽',
                    'label' => 'ดีเซล',
                    'name' => '',
                    'value' => number_format((float)$oil_prices['diesel']['today'], 2),
                    'type' => 'oil',
                    'change' => null
                );
            }
        }
        
        if (empty($ticker_items)) {
            return '<div class="tfd-ticker-banner-empty">ไม่มีข้อมูลแสดง - กรุณาตั้งค่า Make.com webhook หรือใส่ข้อมูลทดสอบ</div>';
        }
        
        ob_start();
        ?>
        <div class="tfd-ticker-banner-wrapper" style="height: <?php echo esc_attr($atts['height']); ?>; background-color: <?php echo esc_attr($atts['bg_color']); ?>; color: <?php echo esc_attr($atts['text_color']); ?>;">
            <div class="tfd-ticker-banner" data-speed="<?php echo esc_attr($atts['speed']); ?>">
                <div class="tfd-ticker-content">
                    <?php foreach ($ticker_items as $item): ?>
                        <div class="tfd-ticker-item tfd-ticker-<?php echo esc_attr($item['type']); ?>">
                            <?php if ($atts['show_flags'] === 'yes' && !empty($item['flag'])): ?>
                                <span class="tfd-ticker-flag"><?php echo $item['flag']; ?></span>
                            <?php endif; ?>
                            <span class="tfd-ticker-label"><?php echo esc_html($item['label']); ?></span>
                            <?php if (!empty($item['name'])): ?>
                                <span class="tfd-ticker-name"><?php echo esc_html($item['name']); ?></span>
                            <?php endif; ?>
                            <span class="tfd-ticker-value"><?php echo esc_html($item['value']); ?></span>
                            <?php if ($item['change'] !== null): ?>
                                <span class="tfd-ticker-change <?php echo $item['change'] >= 0 ? 'positive' : 'negative'; ?>">
                                    <?php echo $item['change'] >= 0 ? '▲' : '▼'; ?>
                                    <?php echo abs($item['change']); ?>%
                                </span>
                            <?php endif; ?>
                        </div>
                    <?php endforeach; ?>
                </div>
                <!-- Duplicate for seamless loop -->
                <div class="tfd-ticker-content" aria-hidden="true">
                    <?php foreach ($ticker_items as $item): ?>
                        <div class="tfd-ticker-item tfd-ticker-<?php echo esc_attr($item['type']); ?>">
                            <?php if ($atts['show_flags'] === 'yes' && !empty($item['flag'])): ?>
                                <span class="tfd-ticker-flag"><?php echo $item['flag']; ?></span>
                            <?php endif; ?>
                            <span class="tfd-ticker-label"><?php echo esc_html($item['label']); ?></span>
                            <?php if (!empty($item['name'])): ?>
                                <span class="tfd-ticker-name"><?php echo esc_html($item['name']); ?></span>
                            <?php endif; ?>
                            <span class="tfd-ticker-value"><?php echo esc_html($item['value']); ?></span>
                            <?php if ($item['change'] !== null): ?>
                                <span class="tfd-ticker-change <?php echo $item['change'] >= 0 ? 'positive' : 'negative'; ?>">
                                    <?php echo $item['change'] >= 0 ? '▲' : '▼'; ?>
                                    <?php echo abs($item['change']); ?>%
                                </span>
                            <?php endif; ?>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
        <?php
        return ob_get_clean();
    }
    
    /**
     * Shortcode: [tfd_exchange_table]
     * แสดงตารางอัตราแลกเปลี่ยนแบบเต็ม เหมือน blogeverydayth
     */
    public function exchange_table_shortcode($atts) {
        $atts = shortcode_atts(array(
            'currencies' => 'USD,EUR,GBP,JPY,CNY,HKD,SGD,MYR,AUD,NZD,CAD,CHF,SEK,NOK,DKK,INR,IDR,PHP', // สกุลเงินที่จะแสดง
            'show_flags' => 'yes',
            'show_change' => 'yes',
            'table_style' => 'modern', // modern, classic, minimal
        ), $atts);
        
        $exchange_rates = get_option('tfd_exchange_rates', array());
        $last_update = get_option('tfd_last_update', '');
        
        if (empty($exchange_rates)) {
            return '<div class="tfd-no-data">ไม่มีข้อมูลอัตราแลกเปลี่ยน กรุณาตั้งค่า Make.com webhook</div>';
        }
        
        // ข้อมูลธงชาติและชื่อเต็ม
        $currency_info = array(
            'USD' => array('flag' => '🇺🇸', 'name' => 'สหรัฐอเมริกา - ดอลลาร์สหรัฐ (USD)'),
            'EUR' => array('flag' => '🇪🇺', 'name' => 'ยูโรโซน - ยูโร (EUR)'),
            'GBP' => array('flag' => '🇬🇧', 'name' => 'อังกฤษ - ปอนด์สเตอร์ลิง (GBP)'),
            'JPY' => array('flag' => '🇯🇵', 'name' => 'ญี่ปุ่น - เยน (100 เยน) (JPY)'),
            'CNY' => array('flag' => '🇨🇳', 'name' => 'จีน - หยวน (CNY)'),
            'HKD' => array('flag' => '🇭🇰', 'name' => 'ฮ่องกง - ดอลลาร์ฮ่องกง (HKD)'),
            'SGD' => array('flag' => '🇸🇬', 'name' => 'สิงคโปร์ - ดอลลาร์สิงคโปร์ (SGD)'),
            'MYR' => array('flag' => '🇲🇾', 'name' => 'มาเลเซีย - ริงกิต (MYR)'),
            'AUD' => array('flag' => '🇦🇺', 'name' => 'ออสเตรเลีย - ดอลลาร์ออสเตรเลีย (AUD)'),
            'NZD' => array('flag' => '🇳🇿', 'name' => 'นิวซีแลนด์ - ดอลลาร์นิวซีแลนด์ (NZD)'),
            'CAD' => array('flag' => '🇨🇦', 'name' => 'แคนาดา - ดอลลาร์แคนาดา (CAD)'),
            'CHF' => array('flag' => '🇨🇭', 'name' => 'สวิตเซอร์แลนด์ - ฟรังก์สวิส (CHF)'),
            'SEK' => array('flag' => '🇸🇪', 'name' => 'สวีเดน - โครนา (SEK)'),
            'NOK' => array('flag' => '🇳🇴', 'name' => 'นอร์เวย์ - โครนา (NOK)'),
            'DKK' => array('flag' => '🇩🇰', 'name' => 'เดนมาร์ก - โครนา (DKK)'),
            'INR' => array('flag' => '🇮🇳', 'name' => 'อินเดีย - รูปี (INR)'),
            'IDR' => array('flag' => '🇮🇩', 'name' => 'อินโดนีเซีย - รูเปียห์ (1,000 รูเปียห์) (IDR)'),
            'PHP' => array('flag' => '🇵🇭', 'name' => 'ฟิลิปปินส์ - เปโซ (PHP)'),
            'KRW' => array('flag' => '🇰🇷', 'name' => 'เกาหลีใต้ - วอน (KRW)'),
            'TWD' => array('flag' => '🇹🇼', 'name' => 'ไต้หวัน - ดอลลาร์ไต้หวัน (TWD)'),
        );
        
        $currencies_array = array_map('trim', explode(',', $atts['currencies']));
        
        ob_start();
        ?>
        <div class="tfd-exchange-table-wrapper tfd-table-<?php echo esc_attr($atts['table_style']); ?>">
            <?php if (!empty($last_update)): ?>
                <div class="tfd-table-header">
                    <p class="tfd-last-update">ข้อมูลอัพเดทล่าสุด: <?php echo esc_html(date('d/m/Y H:i', strtotime($last_update))); ?> น.</p>
                </div>
            <?php endif; ?>
            
            <div class="tfd-table-responsive">
                <table class="tfd-exchange-table">
                    <thead>
                        <tr>
                            <th class="tfd-col-currency">สกุลเงิน</th>
                            <th class="tfd-col-buy">ซื้อเข้า</th>
                            <th class="tfd-col-sell">ขายออก</th>
                            <?php if ($atts['show_change'] === 'yes'): ?>
                                <th class="tfd-col-change">เปลี่ยนแปลง</th>
                            <?php endif; ?>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($currencies_array as $currency): ?>
                            <?php if (isset($exchange_rates[$currency])): ?>
                                <?php 
                                $rates = $exchange_rates[$currency];
                                $info = isset($currency_info[$currency]) ? $currency_info[$currency] : array('flag' => '', 'name' => $currency);
                                ?>
                                <tr class="tfd-currency-row">
                                    <td class="tfd-col-currency">
                                        <?php if ($atts['show_flags'] === 'yes' && !empty($info['flag'])): ?>
                                            <span class="tfd-flag"><?php echo $info['flag']; ?></span>
                                        <?php endif; ?>
                                        <span class="tfd-currency-name"><?php echo esc_html($info['name']); ?></span>
                                    </td>
                                    <td class="tfd-col-buy">
                                        <?php echo isset($rates['buy']) ? esc_html(number_format((float)$rates['buy'], 4)) : '-'; ?>
                                    </td>
                                    <td class="tfd-col-sell">
                                        <?php echo isset($rates['sell']) ? esc_html(number_format((float)$rates['sell'], 4)) : '-'; ?>
                                    </td>
                                    <?php if ($atts['show_change'] === 'yes'): ?>
                                        <td class="tfd-col-change">
                                            <?php if (isset($rates['change'])): ?>
                                                <?php 
                                                $change = (float)$rates['change'];
                                                $change_class = $change >= 0 ? 'positive' : 'negative';
                                                $change_icon = $change >= 0 ? '▲' : '▼';
                                                ?>
                                                <span class="tfd-change <?php echo $change_class; ?>">
                                                    <?php echo $change_icon; ?> <?php echo abs($change); ?>%
                                                </span>
                                            <?php else: ?>
                                                <span class="tfd-change neutral">-</span>
                                            <?php endif; ?>
                                        </td>
                                    <?php endif; ?>
                                </tr>
                            <?php endif; ?>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
            
            <div class="tfd-table-footer">
                <p class="tfd-disclaimer">
                    <small>ข้อมูลอ้างอิงจากธนาคารแห่งประเทศไทย อัตราแลกเปลี่ยนอาจมีการเปลี่ยนแปลงตามสภาวะตลาด</small>
                </p>
            </div>
        </div>
        <?php
        return ob_get_clean();
    }
}
