<?php
if (!defined('ABSPATH')) exit;

class Growfox_Exchange_Table_Widget extends \Elementor\Widget_Base {
    
    public function get_name() {
        return 'growfox-exchange-table';
    }
    
    public function get_title() {
        return 'ตารางอัตราแลกเปลี่ยน';
    }
    
    public function get_icon() {
        return 'eicon-table';
    }
    
    public function get_categories() {
        return ['general'];
    }
    
    protected function register_controls() {
        
        // Content Section
        $this->start_controls_section('content_section', [
            'label' => 'เนื้อหา',
            'tab' => \Elementor\Controls_Manager::TAB_CONTENT,
        ]);
        
        $this->add_control('show_header', [
            'label' => 'แสดงหัวตาราง',
            'type' => \Elementor\Controls_Manager::SWITCHER,
            'label_on' => 'แสดง',
            'label_off' => 'ซ่อน',
            'return_value' => 'yes',
            'default' => 'yes',
        ]);
        
        $this->add_control('show_flags', [
            'label' => 'แสดงธงชาติ',
            'type' => \Elementor\Controls_Manager::SWITCHER,
            'label_on' => 'แสดง',
            'label_off' => 'ซ่อน',
            'return_value' => 'yes',
            'default' => 'yes',
        ]);
        
        $this->add_control('decimal_places', [
            'label' => 'ทศนิยม',
            'type' => \Elementor\Controls_Manager::NUMBER,
            'min' => 0,
            'max' => 6,
            'step' => 1,
            'default' => 4,
        ]);
        
        $this->end_controls_section();
        
        // Style Section
        $this->start_controls_section('style_section', [
            'label' => 'สไตล์',
            'tab' => \Elementor\Controls_Manager::TAB_STYLE,
        ]);
        
        $this->add_control('header_bg_color', [
            'label' => 'สีพื้นหลังหัวตาราง',
            'type' => \Elementor\Controls_Manager::COLOR,
            'default' => '#003d82',
        ]);
        
        $this->add_control('header_text_color', [
            'label' => 'สีตัวอักษรหัวตาราง',
            'type' => \Elementor\Controls_Manager::COLOR,
            'default' => '#ffffff',
        ]);
        
        $this->add_control('row_bg_color', [
            'label' => 'สีพื้นหลังแถว',
            'type' => \Elementor\Controls_Manager::COLOR,
            'default' => '#ffffff',
        ]);
        
        $this->add_control('row_alt_bg_color', [
            'label' => 'สีพื้นหลังแถวสลับ',
            'type' => \Elementor\Controls_Manager::COLOR,
            'default' => '#f5f5f5',
        ]);
        
        $this->add_control('border_color', [
            'label' => 'สีเส้นขอบ',
            'type' => \Elementor\Controls_Manager::COLOR,
            'default' => '#dddddd',
        ]);
        
        $this->end_controls_section();
    }
    
    protected function render() {
        $settings = $this->get_settings_for_display();
        
        // ดึงข้อมูล
        $data = get_option('options_exchange_data');
        if (!$data) {
            echo '<p>ไม่มีข้อมูลอัตราแลกเปลี่ยน</p>';
            return;
        }
        
        $rates = json_decode($data, true);
        if (!$rates || !is_array($rates)) {
            echo '<p>ข้อมูลไม่ถูกต้อง</p>';
            return;
        }
        
        $period = get_option('options_period');
        $decimal = $settings['decimal_places'];
        
        ?>
        <div class="growfox-exchange-table-wrapper">
            <?php if ($period): ?>
                <div class="table-date">
                    <p>ประจำวันที่ <?php echo date('d/m/Y', strtotime($period)); ?></p>
                </div>
            <?php endif; ?>
            
            <div class="table-responsive">
                <table class="growfox-exchange-table">
                    <?php if ($settings['show_header'] === 'yes'): ?>
                    <thead>
                        <tr>
                            <th rowspan="2">ประเทศ</th>
                            <th rowspan="2">สกุลเงิน</th>
                            <th colspan="2">อัตราซื้อถัวเฉลี่ย</th>
                            <th rowspan="2">อัตราขายถัวเฉลี่ย</th>
                        </tr>
                        <tr>
                            <th>ซื้อตั๋วเงิน</th>
                            <th>ซื้อเงินโอน</th>
                        </tr>
                    </thead>
                    <?php endif; ?>
                    <tbody>
                        <?php foreach ($rates as $rate): ?>
                        <tr>
                            <td>
                                <?php if ($settings['show_flags'] === 'yes'): ?>
                                    <div class="currency-cell">
                                        <img src="https://flagcdn.com/w40/<?php echo $this->get_flag_code($rate['currency_id']); ?>.png" 
                                             alt="<?php echo esc_attr($rate['currency_id']); ?>" 
                                             class="currency-flag">
                                        <span><?php echo esc_html($rate['currency_name_th'] ?? ''); ?></span>
                                    </div>
                                <?php else: ?>
                                    <?php echo esc_html($rate['currency_name_th'] ?? ''); ?>
                                <?php endif; ?>
                            </td>
                            <td class="text-center"><strong><?php echo esc_html($rate['currency_id']); ?></strong></td>
                            <td class="text-right"><?php echo number_format((float)$rate['buying_sight'], $decimal); ?></td>
                            <td class="text-right"><?php echo number_format((float)$rate['buying_transfer'], $decimal); ?></td>
                            <td class="text-right"><?php echo number_format((float)$rate['selling'], $decimal); ?></td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
        
        <style>
        .growfox-exchange-table-wrapper {
            width: 100%;
            margin: 20px 0;
        }
        
        .table-date {
            margin-bottom: 15px;
            font-size: 16px;
            font-weight: 600;
        }
        
        .table-responsive {
            overflow-x: auto;
        }
        
        .growfox-exchange-table {
            width: 100%;
            border-collapse: collapse;
            border: 1px solid <?php echo $settings['border_color']; ?>;
        }
        
        .growfox-exchange-table thead tr {
            background-color: <?php echo $settings['header_bg_color']; ?>;
            color: <?php echo $settings['header_text_color']; ?>;
        }
        
        .growfox-exchange-table th {
            padding: 12px 15px;
            text-align: center;
            font-weight: 600;
            border: 1px solid <?php echo $settings['border_color']; ?>;
        }
        
        .growfox-exchange-table td {
            padding: 10px 15px;
            border: 1px solid <?php echo $settings['border_color']; ?>;
        }
        
        .growfox-exchange-table tbody tr:nth-child(even) {
            background-color: <?php echo $settings['row_alt_bg_color']; ?>;
        }
        
        .growfox-exchange-table tbody tr:nth-child(odd) {
            background-color: <?php echo $settings['row_bg_color']; ?>;
        }
        
        .growfox-exchange-table tbody tr:hover {
            background-color: #e8f4f8;
        }
        
        .currency-cell {
            display: flex;
            align-items: center;
            gap: 10px;
        }
        
        .currency-flag {
            width: 30px;
            height: 20px;
            object-fit: cover;
            border-radius: 2px;
        }
        
        .text-center {
            text-align: center;
        }
        
        .text-right {
            text-align: right;
        }
        
        @media print {
            .growfox-exchange-table {
                font-size: 12px;
            }
            
            .growfox-exchange-table th,
            .growfox-exchange-table td {
                padding: 8px 10px;
            }
        }
        </style>
        <?php
    }
    
    // แปลง Currency Code เป็น Country Code สำหรับธง
    private function get_flag_code($currency_id) {
        $flags = [
            'USD' => 'us', 'GBP' => 'gb', 'EUR' => 'eu', 'JPY' => 'jp',
            'HKD' => 'hk', 'MYR' => 'my', 'SGD' => 'sg', 'BND' => 'bn',
            'PHP' => 'ph', 'IDR' => 'id', 'INR' => 'in', 'CHF' => 'ch',
            'AUD' => 'au', 'NZD' => 'nz', 'CAD' => 'ca', 'SEK' => 'se',
            'DKK' => 'dk', 'NOK' => 'no', 'CNY' => 'cn',
        ];
        
        return $flags[strtoupper($currency_id)] ?? 'xx';
    }
}
