<?php
/**
 * Admin Menu
 */

if (!defined('ABSPATH')) {
    exit;
}

class TFD_Admin_Menu {
    
    private static $instance = null;
    
    public static function get_instance() {
        if (null === self::$instance) {
            self::$instance = new self();
        }
        return self::$instance;
    }
    
    private function __construct() {
        add_action('admin_menu', array($this, 'add_menu'));
        add_action('admin_init', array($this, 'register_settings'));
    }
    
    public function add_menu() {
        add_menu_page(
            'Thailand Financial Data',
            'Financial Data',
            'manage_options',
            'thailand-financial-data',
            array($this, 'render_dashboard'),
            'dashicons-chart-line',
            30
        );
        
        add_submenu_page(
            'thailand-financial-data',
            'ตั้งค่า',
            'ตั้งค่า',
            'manage_options',
            'thailand-financial-settings',
            array($this, 'render_settings')
        );
    }
    
    public function register_settings() {
        register_setting('tfd_settings', 'tfd_api_key');
    }
    
    public function render_dashboard() {
        $exchange_rates = get_option('tfd_exchange_rates', array());
        $oil_prices = get_option('tfd_oil_prices', array());
        $gold_prices = get_option('tfd_gold_prices', array());
        
        $exchange_updated = get_option('tfd_exchange_rates_updated', '-');
        $oil_updated = get_option('tfd_oil_prices_updated', '-');
        $gold_updated = get_option('tfd_gold_prices_updated', '-');
        
        ?>
        <div class="wrap">
            <h1>Thailand Financial Data Dashboard</h1>
            
            <div class="card" style="max-width: 100%; margin-top: 20px;">
                <h2>📊 API Endpoints</h2>
                <p>ใช้ URL เหล่านี้ใน Make.com:</p>
                <table class="widefat" style="margin-top: 10px;">
                    <thead>
                        <tr>
                            <th>ประเภทข้อมูล</th>
                            <th>Endpoint URL</th>
                            <th>Method</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td><strong>อัตราแลกเปลี่ยน</strong></td>
                            <td><code><?php echo rest_url('thailand-financial/v1/exchange-rates'); ?></code></td>
                            <td>POST</td>
                        </tr>
                        <tr>
                            <td><strong>ราคาน้ำมัน</strong></td>
                            <td><code><?php echo rest_url('thailand-financial/v1/oil-prices'); ?></code></td>
                            <td>POST</td>
                        </tr>
                        <tr>
                            <td><strong>ราคาทอง</strong></td>
                            <td><code><?php echo rest_url('thailand-financial/v1/gold-prices'); ?></code></td>
                            <td>POST</td>
                        </tr>
                    </tbody>
                </table>
            </div>
            
            <div class="card" style="max-width: 100%; margin-top: 20px;">
                <h2>💱 อัตราแลกเปลี่ยน</h2>
                <p><strong>อัปเดตล่าสุด:</strong> <?php echo $exchange_updated; ?></p>
                <?php if (!empty($exchange_rates)): ?>
                    <table class="widefat">
                        <thead>
                            <tr>
                                <th>สกุลเงิน</th>
                                <th>ราคาซื้อ</th>
                                <th>ราคาขาย</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($exchange_rates as $currency => $rates): ?>
                                <tr>
                                    <td><strong><?php echo esc_html($currency); ?></strong></td>
                                    <td><?php echo isset($rates['buy']) ? esc_html($rates['buy']) : '-'; ?></td>
                                    <td><?php echo isset($rates['sell']) ? esc_html($rates['sell']) : '-'; ?></td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                <?php else: ?>
                    <p style="color: #999;">ยังไม่มีข้อมูล</p>
                <?php endif; ?>
            </div>
            
            <div class="card" style="max-width: 100%; margin-top: 20px;">
                <h2>⛽ ราคาน้ำมัน</h2>
                <p><strong>อัปเดตล่าสุด:</strong> <?php echo $oil_updated; ?></p>
                <?php if (!empty($oil_prices)): ?>
                    <table class="widefat">
                        <thead>
                            <tr>
                                <th>ชนิดน้ำมัน</th>
                                <th>ราคาวันนี้</th>
                                <th>ราคาพรุ่งนี้</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($oil_prices as $type => $prices): ?>
                                <tr>
                                    <td><strong><?php echo esc_html($type); ?></strong></td>
                                    <td><?php echo isset($prices['today']) ? esc_html($prices['today']) : '-'; ?></td>
                                    <td><?php echo isset($prices['tomorrow']) ? esc_html($prices['tomorrow']) : '-'; ?></td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                <?php else: ?>
                    <p style="color: #999;">ยังไม่มีข้อมูล</p>
                <?php endif; ?>
            </div>
            
            <div class="card" style="max-width: 100%; margin-top: 20px;">
                <h2>🏆 ราคาทอง</h2>
                <p><strong>อัปเดตล่าสุด:</strong> <?php echo $gold_updated; ?></p>
                <?php if (!empty($gold_prices)): ?>
                    <table class="widefat">
                        <thead>
                            <tr>
                                <th>ประเภท</th>
                                <th>รับซื้อ</th>
                                <th>ขายออก</th>
                                <th>เปลี่ยนแปลง</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td><strong>ทองคำแท่ง</strong></td>
                                <td><?php echo isset($gold_prices['bar_buy']) ? esc_html($gold_prices['bar_buy']) : '-'; ?></td>
                                <td><?php echo isset($gold_prices['bar_sell']) ? esc_html($gold_prices['bar_sell']) : '-'; ?></td>
                                <td><?php echo isset($gold_prices['change']) ? esc_html($gold_prices['change']) : '-'; ?></td>
                            </tr>
                            <tr>
                                <td><strong>ทองรูปพรรณ</strong></td>
                                <td><?php echo isset($gold_prices['jewelry_buy']) ? esc_html($gold_prices['jewelry_buy']) : '-'; ?></td>
                                <td><?php echo isset($gold_prices['jewelry_sell']) ? esc_html($gold_prices['jewelry_sell']) : '-'; ?></td>
                                <td>-</td>
                            </tr>
                        </tbody>
                    </table>
                <?php else: ?>
                    <p style="color: #999;">ยังไม่มีข้อมูล</p>
                <?php endif; ?>
            </div>
        </div>
        <?php
    }
    
    public function render_settings() {
        if (isset($_POST['tfd_save_settings'])) {
            check_admin_referer('tfd_settings_nonce');
            update_option('tfd_api_key', sanitize_text_field($_POST['tfd_api_key']));
            echo '<div class="notice notice-success"><p>บันทึกการตั้งค่าสำเร็จ</p></div>';
        }
        
        $api_key = get_option('tfd_api_key', '');
        ?>
        <div class="wrap">
            <h1>ตั้งค่า Thailand Financial Data</h1>
            
            <form method="post">
                <?php wp_nonce_field('tfd_settings_nonce'); ?>
                
                <table class="form-table">
                    <tr>
                        <th scope="row">
                            <label for="tfd_api_key">API Key (ไม่บังคับ)</label>
                        </th>
                        <td>
                            <input type="text" 
                                   id="tfd_api_key" 
                                   name="tfd_api_key" 
                                   value="<?php echo esc_attr($api_key); ?>" 
                                   class="regular-text">
                            <p class="description">
                                ถ้าต้องการความปลอดภัย ให้ตั้ง API Key และใส่ใน Header ของ Make.com<br>
                                Header: <code>X-API-Key: your-api-key-here</code>
                            </p>
                        </td>
                    </tr>
                </table>
                
                <p class="submit">
                    <button type="submit" name="tfd_save_settings" class="button button-primary">บันทึกการตั้งค่า</button>
                </p>
            </form>
        </div>
        <?php
    }
}
