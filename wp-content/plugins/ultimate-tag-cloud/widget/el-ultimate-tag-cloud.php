<?php

use Elementor\Repeater;
use Elementor\Utils;
use Elementor\Group_Control_Css_Filter;
use Elementor\Controls_Manager;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Group_Control_Typography;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Text_Stroke;
use Elementor\Group_Control_Background;
use Elementor\Group_Control_Image_Size;
use Elementor\Icons_Manager;


defined( 'ABSPATH' ) || die();

class Ultimate_Tag_Cloud_Register_Elementor_Widget extends \Elementor\Widget_Base {
    public function get_name() {
        return 'ultimate_tag_cloud_el_widget';
    }

    public function get_title() {
        return __('Tag Cloud', 'ultimate-tag-cloud');
    }

    public function get_icon() {
        return 'eicon-theme-builder ultc-badge';
    }

    public function get_categories() {
        return ['ultimate_tag_cloud_category'];
    }

    public function get_keywords() {
        return ['tag', 'rs', 'ultimate', 'query', 'ultimate tag cloud', 'cat'];
    }

    /**
     * Register widget controls.
     *
     * @since 1.0.0
     * @access protected
     */
    protected function register_controls()
    {
        // Configuration Start
        $this->start_controls_section(
            '_configuration_section',
            [
                'label' => esc_html__('Configuration', 'ultimate-tag-cloud'),
                'tab' => Controls_Manager::TAB_CONTENT,
            ]
        );
            $this->add_control(
                'tag_style',
                [
                    'label' => esc_html__( 'Tag Style', 'ultimate-tag-cloud' ),
                    'type' => Controls_Manager::SELECT,
                    'default' => 'default',
                    'options' => [
                        'default' => esc_html__( 'Default', 'ultimate-tag-cloud' ),
                        'label' => esc_html__( 'Label', 'ultimate-tag-cloud' ),
                        'block' => esc_html__( 'Block', 'ultimate-tag-cloud' ),
                        'ribbon' => esc_html__( 'Ribbon', 'ultimate-tag-cloud' )
                    ],
                ]
            );
            $this->add_control(
                'ribbon_style',
                [
                    'label' => esc_html__( 'Ribbon Style', 'ultimate-tag-cloud' ),
                    'type' => Controls_Manager::SELECT,
                    'default' => 'style1',
                    'options' => [
                        'style1' => esc_html__( 'Style 1', 'ultimate-tag-cloud' ),
                        'style2' => esc_html__( 'Style 2', 'ultimate-tag-cloud' ),
                        'style3' => esc_html__( 'Style 3', 'ultimate-tag-cloud' ),
                    ],
                    'condition' => [
                        'tag_style' => 'ribbon',
                    ],
                ]
            );
            $this->add_control(
                'show_count',
                [
                    'label' => esc_html__( 'Show Count', 'ultimate-tag-cloud' ),
                    'type' => Controls_Manager::SWITCHER,
                    'label_on' => esc_html__( 'Yes', 'ultimate-tag-cloud' ),
                    'label_off' => esc_html__( 'No', 'ultimate-tag-cloud' ),
                    'return_value' => 'yes',
                    'default' => 'yes',
                    'separator' => 'before',
                ]
            );
            $this->add_control(
                'random_color',
                [
                    'label' => esc_html__( 'Random Color', 'ultimate-tag-cloud' ),
                    'type' => Controls_Manager::SWITCHER,
                    'label_on' => esc_html__( 'Yes', 'ultimate-tag-cloud' ),
                    'label_off' => esc_html__( 'No', 'ultimate-tag-cloud' ),
                    'return_value' => 'yes',
                    'default' => 'yes',
                    'separator' => 'before',
                ]
            );
            $this->add_control(
                'random_color_options',
                [
                    'label' => __('Custom Colors', 'ultimate-tag-cloud'),
                    'placeholder' => __('#FF0000, #00FF00, #0000FF', 'ultimate-tag-cloud'),
                    'type' => Controls_Manager::TEXT,
                    'label_block' => true,
                    'description' => __('Enter colors as HEX values, separated by commas (e.g., #FF0000, #00FF00, #0000FF)', 'ultimate-tag-cloud'),
                    'condition' => [
                        'random_color' => 'yes',
                    ],
                ]
            );
            $this->add_control(
                'random_size',
                [
                    'label' => esc_html__( 'Random Size', 'ultimate-tag-cloud' ),
                    'type' => Controls_Manager::SWITCHER,
                    'label_on' => esc_html__( 'Yes', 'ultimate-tag-cloud' ),
                    'label_off' => esc_html__( 'No', 'ultimate-tag-cloud' ),
                    'return_value' => 'yes',
                    'default' => 'no',
                    'separator' => 'before',
                ]
            );
            $this->add_control(
                'min_size',
                [
                    'label' => esc_html__( 'Min Size', 'ultimate-tag-cloud' ),
                    'type' => Controls_Manager::NUMBER,
                    'min' => 5,
                    'max' => 100,
                    'default' => 14,
                    'condition' => [
                        'random_size' => 'yes',
                    ],
                ]
            );
            $this->add_control(
                'max_size',
                [
                    'label' => esc_html__( 'Max Size', 'ultimate-tag-cloud' ),
                    'type' => Controls_Manager::NUMBER,
                    'min' => 5,
                    'max' => 100,
                    'default' => 25,
                    'condition' => [
                        'random_size' => 'yes',
                    ],
                ]
            );
        $this->end_controls_section();
        // Configuration End

        // Query Start
        $this->start_controls_section(
			'_query_section',
			[
				'label'     => __( 'Query', 'ultimate-tag-cloud' ),
                'tab' => Controls_Manager::TAB_CONTENT,
			]
		);
            $post_types = ultimate_tag_cloud_get_posts_types();
            $this->add_control(
                'post_type_filter',
                [
                    'label'       => __( 'Post Type', 'ultimate-tag-cloud' ),
                    'type'        => Controls_Manager::SELECT,
                    'label_block' => true,
                    'options'     => $post_types,
                    'default'     => 'post',
                ]
            );
            $this->add_control(
                'filter_tabs_type',
                [
                    'label'     => __( 'Source', 'ultimate-tag-cloud' ),
                    'type'      => Controls_Manager::SELECT,
                    'default'   => 'category',
                    'options' => [
                        'category' => esc_html__( 'Category', 'ultimate-tag-cloud' ),
                        'tag' => esc_html__( 'Tag', 'ultimate-tag-cloud' ),
                    ],
                ]
            );

            $this->add_control(
                'item_limit',
                [
                    'label' => __( 'Item Limit', 'ultimate-tag-cloud' ),
                    'type' => Controls_Manager::NUMBER,
                    'separator' => 'before',
                ]
            );
    
            $this->add_control(
                'hide_empty',
                [
                    'label'       => __( 'Hide Empty', 'ultimate-tag-cloud' ),
                    'type'        => Controls_Manager::SELECT,
                    'separator'   => 'before',
                    'label_block' => true,
                    'options'     => [
                        'true'        => __( 'True', 'ultimate-tag-cloud' ),
                        'false'        => __( 'False', 'ultimate-tag-cloud' ),
                    ],
                    'default'     => 'true'
                ]
            );
            $this->add_control(
                'order_by',
                [
                    'label'       => __( 'Order By', 'ultimate-tag-cloud' ),
                    'type'        => Controls_Manager::SELECT,
                    'separator'   => 'before',
                    'label_block' => true,
                    'options'     => [
                        'none'        => __( 'None', 'ultimate-tag-cloud' ),
                        'name'        => __( 'Name Alphabetically', 'ultimate-tag-cloud' ),
                        'slug'        => __( 'Slug Alphabetically', 'ultimate-tag-cloud' ),
                        'description' => __( 'Description Alphabetically', 'ultimate-tag-cloud' ),
                        'ID'          => __( 'Term ID', 'ultimate-tag-cloud' ),
                        'count'       => __( 'Posts Number', 'ultimate-tag-cloud' ),
                        'random'      => __( 'Random', 'ultimate-tag-cloud' ),
                    ],
                    'default'     => 'none'
                ]
            );
    
            $this->add_control(
                'order',
                [
                    'label'       => __( 'Order', 'ultimate-tag-cloud' ),
                    'type'        => Controls_Manager::SELECT,
                    'label_block' => true,
                    'options'     => [
                        'ASC'  => __( 'Ascending', 'ultimate-tag-cloud' ),
                        'DESC' => __( 'Descending', 'ultimate-tag-cloud' ),
                    ],
                    'default'     => 'ASC'
                ]
            );
        $this->end_controls_section();
        // Query End
        
        // Wrapper Style Start
        $this->start_controls_section(
			'_wrapper_style_section',
			[
				'label'     => __( 'Wrapper', 'ultimate-tag-cloud' ),
                'tab' => Controls_Manager::TAB_STYLE,
			]
		);
            $this->add_responsive_control(
                'wrapper_h_align',
                [
                    'label' => esc_html__( 'Horizontal Align', 'ultimate-tag-cloud' ),
                    'type' => Controls_Manager::CHOOSE,
                    'options' => [
                        'flex-start' => [
                            'title' => esc_html__( 'Start', 'ultimate-tag-cloud' ),
                            'icon' => 'eicon-align-start-h',
                        ],
                        'center' => [
                            'title' => esc_html__( 'Center', 'ultimate-tag-cloud' ),
                            'icon' => 'eicon-align-center-h',
                        ],
                        'flex-end' => [
                            'title' => esc_html__( 'End', 'ultimate-tag-cloud' ),
                            'icon' => 'eicon-align-end-h',
                        ],
                        'space-between' => [
                            'title' => esc_html__( 'Space Between', 'ultimate-tag-cloud' ),
                            'icon' => 'eicon-justify-space-between-h',
                        ],

                    ],
                    'toggle' => true,
                    'selectors' => [
                        'body {{WRAPPER}} .ultimate-tag-cloud-container .ultimate-tag-cloud-words' => 'justify-content: {{VALUE}};',
                    ],
                ]
            );
            $this->add_responsive_control(
                'wrapper_vertical_align',
                [
                    'label' => esc_html__( 'Vertical Align', 'ultimate-tag-cloud' ),
                    'type' => Controls_Manager::CHOOSE,
                    'options' => [
                        'flex-start' => [
                            'title' => esc_html__( 'Top', 'ultimate-tag-cloud' ),
                            'icon' => 'eicon-align-start-v',
                        ],
                        'center' => [
                            'title' => esc_html__( 'Middle', 'ultimate-tag-cloud' ),
                            'icon' => 'eicon-align-center-v',
                        ],
                        'flex-end' => [
                            'title' => esc_html__( 'Bottom', 'ultimate-tag-cloud' ),
                            'icon' => 'eicon-align-end-v',
                        ],
                    ],
                    'toggle' => true,
                    'selectors' => [
                        'body {{WRAPPER}} .ultimate-tag-cloud-container .ultimate-tag-cloud-words' => 'align-items: {{VALUE}};',
                    ],
                ]
            );
            $this->add_responsive_control(
                'wrapper_flex_wrap',
                [
                    'label' => esc_html__( 'Flex Wrap', 'ultimate-tag-cloud' ),
                    'type' => Controls_Manager::CHOOSE,
                    'options' => [
                        'nowrap' => [
                            'title' => esc_html__( 'No Wrap', 'ultimate-tag-cloud' ),
                            'icon' => 'eicon-nowrap',
                        ],
                        'wrap' => [
                            'title' => esc_html__( 'Wrap', 'ultimate-tag-cloud' ),
                            'icon' => 'eicon-wrap',
                        ],
                    ],
                    'toggle' => true,
                    'selectors' => [
                        'body {{WRAPPER}} .ultimate-tag-cloud-container .ultimate-tag-cloud-words' => 'flex-wrap: {{VALUE}};',
                    ],
                ]
            );
            $this->add_responsive_control(
                'wrapper_gap_between_word',
                [
                    'label' => esc_html__( 'Space Between', 'ultimate-tag-cloud' ),
                    'type' => Controls_Manager::SLIDER,
                    'size_units' => [ 'px', '%', 'custom' ],
                    'range' => [
                        'px' => [
                            'min' => 0,
                            'max' => 1000,
                        ],
                        '%' => [
                            'min' => 0,
                            'max' => 100,
                        ],
                    ],
                    'selectors' => [
                        'body {{WRAPPER}} .ultimate-tag-cloud-container .ultimate-tag-cloud-words' => 'gap: {{SIZE}}{{UNIT}};',
                    ],
                ]
            );
            $this->add_responsive_control(
                'wrapper_margin',
                [
                    'label' => esc_html__( 'Margin', 'ultimate-tag-cloud' ),
                    'type' => Controls_Manager::DIMENSIONS,
                    'size_units' => [ 'px', '%', 'custom' ],
                    'separator' => 'before',
                    'selectors' => [
                        'body {{WRAPPER}} .ultimate-tag-cloud-container' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    ],
                ]
            );
            $this->add_responsive_control(
                'wrapper_padding',
                [
                    'label' => esc_html__( 'Padding', 'ultimate-tag-cloud' ),
                    'type' => Controls_Manager::DIMENSIONS,
                    'size_units' => [ 'px', '%', 'custom' ],
                    'selectors' => [
                        'body {{WRAPPER}} .ultimate-tag-cloud-container .ultimate-tag-cloud-words' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    ],
                ]
            );
            $this->add_responsive_control(
                'wrapper_border_radius',
                [
                    'label' => esc_html__( 'Border Radius', 'ultimate-tag-cloud' ),
                    'type' => Controls_Manager::DIMENSIONS,
                    'size_units' => [ 'px', '%', 'custom' ],
                    'selectors' => [
                        'body {{WRAPPER}} .ultimate-tag-cloud-container .ultimate-tag-cloud-words' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    ],
                ]
            );
            $this->add_group_control(
                Group_Control_Background::get_type(),
                [
                    'name' => 'wrapper_background',
                    'types' => [ 'classic', 'gradient' ],
                    'selector' => 'body {{WRAPPER}} .ultimate-tag-cloud-container .ultimate-tag-cloud-words',
                ]
            );
            $this->add_group_control(
                Group_Control_Border::get_type(),
                [
                    'name' => 'wrapper_border',
                    'selector' => 'body {{WRAPPER}} .ultimate-tag-cloud-container .ultimate-tag-cloud-words',
                ]
            );
            $this->add_group_control(
                Group_Control_Box_Shadow::get_type(),
                [
                    'name' => 'wrapper_box_shadow',
                    'selector' => 'body {{WRAPPER}} .ultimate-tag-cloud-container .ultimate-tag-cloud-words',
                ]
            );
        $this->end_controls_section();
        // Wrapper Style End

        // Tag Style Start
        $this->start_controls_section(
            '_tag_style_section',
            [
                'label' => __( 'Tag Pill Style', 'ultimate-tag-cloud' ),
                'tab' => Controls_Manager::TAB_STYLE,   
            ]
        );
            $this->add_group_control(
                Group_Control_Typography::get_type(),
                [
                    'name' => 'tag_typography',
                    'selector' => 'body {{WRAPPER}} .ultimate-tag-cloud-container .ultimate-tag-cloud-words .tag-word-wrap a',
                ]
            );
            $this->add_responsive_control(
                'tag_margin',
                [
                    'label' => esc_html__( 'Margin', 'ultimate-tag-cloud' ),
                    'type' => Controls_Manager::DIMENSIONS,
                    'size_units' => [ 'px', '%', 'custom' ],
                    'separator' => 'before',
                    'selectors' => [
                        'body {{WRAPPER}} .ultimate-tag-cloud-container .ultimate-tag-cloud-words .tag-word-wrap' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    ],
                ]
            );
            $this->add_responsive_control(
                'tag_padding',
                [
                    'label' => esc_html__( 'Padding', 'ultimate-tag-cloud' ),
                    'type' => Controls_Manager::DIMENSIONS,
                    'size_units' => [ 'px', '%', 'custom' ],
                    'selectors' => [
                        'body {{WRAPPER}} .ultimate-tag-cloud-container .ultimate-tag-cloud-words .tag-word-wrap a' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    ],
                ]
            );
            $this->add_responsive_control(
                'tag_border_radius',
                [   
                    'label' => esc_html__( 'Border Radius', 'ultimate-tag-cloud' ),
                    'type' => Controls_Manager::DIMENSIONS,
                    'size_units' => [ 'px', '%', 'custom' ],
                    'selectors' => [
                        'body {{WRAPPER}} .ultimate-tag-cloud-container .ultimate-tag-cloud-words .tag-word-wrap a' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    ],
                ]
            );

            $this->start_controls_tabs(
                'tag_style_tabs'
            );
                $this->start_controls_tab(
                    'tag_style_normal_tab',
                    [
                        'label' => esc_html__( 'Normal', 'ultimate-tag-cloud' ),
                    ]
                );
                    $this->add_control(
                        'tag_text_color',
                        [
                            'label' => esc_html__( 'Text Color', 'ultimate-tag-cloud' ),
                            'type' => Controls_Manager::COLOR,
                            'selectors' => [
                                'body {{WRAPPER}} .ultimate-tag-cloud-container .ultimate-tag-cloud-words .tag-word-wrap a' => 'color: {{VALUE}}',
                            ],
                        ]
                    );
                    $this->add_group_control(
                        Group_Control_Background::get_type(),
                        [
                            'name' => 'tag_background',
                            'types' => [ 'classic', 'gradient' ],
                            'selector' => 'body {{WRAPPER}} .ultimate-tag-cloud-container .ultimate-tag-cloud-words .tag-word-wrap a',
                        ]
                    );
                    $this->add_group_control(
                        Group_Control_Border::get_type(),
                        [
                            'name' => 'tag_border',
                            'selector' => 'body {{WRAPPER}} .ultimate-tag-cloud-container .ultimate-tag-cloud-words .tag-word-wrap a',
                        ]
                    );
                    $this->add_group_control(
                        Group_Control_Box_Shadow::get_type(),
                        [
                            'name' => 'tag_box_shadow',
                            'selector' => 'body {{WRAPPER}} .ultimate-tag-cloud-container .ultimate-tag-cloud-words .tag-word-wrap a',
                        ]
                    );
                $this->end_controls_tab();
                $this->start_controls_tab(
                    'tag_style_hover_tab',
                    [
                        'label' => esc_html__( 'Hover', 'ultimate-tag-cloud' ),
                    ]
                );
                    $this->add_control(
                        'tag_text_color_hover',
                        [
                            'label' => esc_html__( 'Text Color', 'ultimate-tag-cloud' ),
                            'type' => Controls_Manager::COLOR,
                            'selectors' => [
                                'body {{WRAPPER}} .ultimate-tag-cloud-container .ultimate-tag-cloud-words .tag-word-wrap a:hover' => 'color: {{VALUE}}',
                            ],
                        ]
                    );
                    $this->add_group_control(
                        Group_Control_Background::get_type(),
                        [
                            'name' => 'tag_background_hover',
                            'types' => [ 'classic', 'gradient' ],
                            'selector' => 'body {{WRAPPER}} .ultimate-tag-cloud-container .ultimate-tag-cloud-words .tag-word-wrap a:hover',
                        ]
                    );
                    $this->add_control(
                        'tag_border_color_hover',
                        [
                            'label' => esc_html__( 'Border Color', 'ultimate-tag-cloud' ),
                            'type' => Controls_Manager::COLOR,
                            'selectors' => [
                                'body {{WRAPPER}} .ultimate-tag-cloud-container .ultimate-tag-cloud-words .tag-word-wrap a:hover' => 'border-color: {{VALUE}}',
                            ],
                        ]
                    );
                    $this->add_group_control(
                        Group_Control_Box_Shadow::get_type(),
                        [
                            'name' => 'tag_box_shadow_hover',
                            'selector' => 'body {{WRAPPER}} .ultimate-tag-cloud-container .ultimate-tag-cloud-words .tag-word-wrap a:hover',
                        ]
                    );
                $this->end_controls_tab();
            $this->end_controls_tabs();
        $this->end_controls_section();
        // Tag Style End
        
        // Label Style Start
        $this->start_controls_section(
            '_label_style_section',
            [
                'label' => __( 'Label Style', 'ultimate-tag-cloud' ),
                'tab' => Controls_Manager::TAB_STYLE,
                'condition' => [
                    'tag_style' => 'label',
                ],
            ]
        );
            $this->add_control(
                'label_dot_color',
                [
                    'label' => esc_html__( 'Dot Color', 'ultimate-tag-cloud' ),
                    'type' => Controls_Manager::COLOR,
                    'selectors' => [
                        'body {{WRAPPER}} .ultimate-tag-cloud-container.style-label .ultimate-tag-cloud-words .tag-word-wrap:before' => 'background-color: {{VALUE}}',
                    ],
                ]
            );
            $this->add_responsive_control(
                'label_dot_size',
                [
                    'label' => esc_html__( 'Dot Size', 'ultimate-tag-cloud' ),
                    'type' => Controls_Manager::SLIDER,
                    'size_units' => [ 'px', '%', 'custom' ],
                    'range' => [
                        'px' => [
                            'min' => 0,
                            'max' => 1000,
                        ],
                        '%' => [
                            'min' => 0,
                            'max' => 100,
                        ],
                    ],
                    'selectors' => [
                        'body {{WRAPPER}} .ultimate-tag-cloud-container.style-label .ultimate-tag-cloud-words .tag-word-wrap:before' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}};',
                    ],
                ]
            );
            $this->add_responsive_control(
                'label_dot_p_y',
                [
                    'label' => esc_html__( 'Dot Position Y', 'ultimate-tag-cloud' ),
                    'type' => Controls_Manager::SLIDER,
                    'size_units' => [ 'px', '%', 'custom' ],
                    'range' => [
                        'px' => [
                            'min' => 0,
                            'max' => 1000,
                        ],
                        '%' => [
                            'min' => 0,
                            'max' => 100,
                        ],
                    ],
                    'default' => [
                        'unit' => '%',
                    ],
                    'selectors' => [
                        'body {{WRAPPER}} .ultimate-tag-cloud-container.style-label .ultimate-tag-cloud-words .tag-word-wrap:before' => 'top: {{SIZE}}{{UNIT}}; transform: translateY(-{{SIZE}}{{UNIT}});',
                    ],
                ]
            );
            $this->add_responsive_control(
                'label_dot_p_x',
                [
                    'label' => esc_html__( 'Dot Position X', 'ultimate-tag-cloud' ),
                    'type' => Controls_Manager::SLIDER,
                    'size_units' => [ 'px', '%', 'custom' ],
                    'range' => [
                        '%' => [
                            'min' => 0,
                            'max' => 100,
                        ],
                        'px' => [
                            'min' => 0,
                            'max' => 1000,
                        ],
                    ],  
                    'selectors' => [
                        'body {{WRAPPER}} .ultimate-tag-cloud-container.style-label .ultimate-tag-cloud-words .tag-word-wrap:before' => 'left: {{SIZE}}{{UNIT}};',
                    ],
                ]
            );
            
        $this->end_controls_section();
        // Label Style End
    }

    /**
     * Render Widget Content to Frontend.
     *
     * @since 1.0.0
     * @access protected
     */
    protected function render() {
        $settings = $this->get_settings_for_display();
        $id = 'uni-'.$this->get_id();
        
        $tag_style = $settings['tag_style'];
        $ribbon_style = !empty($settings['ribbon_style']) ? 'ribbon-'.$settings['ribbon_style'] : '';
        $post_type = $settings['post_type_filter'];
        $selected_taxonomy = $settings['filter_tabs_type'];
        $order_by = $settings['order_by'] ;
        $order = $settings['order'];
        $hide_empty = $settings['hide_empty'] === 'false' ? false : true;
        $item_limit = $settings['item_limit'];
        $taxonomies = get_object_taxonomies($post_type, 'names');
    
        if ($selected_taxonomy === 'category' && in_array('category', $taxonomies)) {
            $taxonomies = ['category'];
        } elseif ($selected_taxonomy === 'tag' && in_array('post_tag', $taxonomies)) {
            $taxonomies = ['post_tag'];
        }
    
        $words_array = [];
        
        $cssVarColors = '';
        $cssVarNumber = '';
        $randColorCls = $settings['random_color'] === 'yes' ? 'rand-color' : '';
        $randSizeCls = $settings['random_size'] === 'yes' ? 'rand-size' : '';
    
        foreach ($taxonomies as $taxonomy) {
            $terms = get_terms([
                'taxonomy'   => $taxonomy,
                'hide_empty' => $hide_empty,
                'orderby'    => $order_by,
                'order'      => $order,
                'number'     => $item_limit,
            ]);
    
            if (!is_wp_error($terms) && !empty($terms)) {
                foreach ($terms as $term) {
                    $term_link = get_term_link($term);
    
                    if (!is_wp_error($term_link)) {
                        $rand_color = $this->get_random_color();
                        $rand_color_high = $this->make_color_high($rand_color);
                        $rand_number = $this->get_random_number($settings['min_size'], $settings['max_size']);
                        $words_array[] = [
                            'name'  => $term->name,
                            'show_count' => $settings['show_count'] === 'yes' ? true : false,
                            'count' => $term->count,
                            'link'  => $term_link,
                            'cls_rand_color' => $randColorCls,
                            'rand_color' => $rand_color,
                            'rand_color_high' => $rand_color_high,
                            'cls_rand_number' => $randSizeCls,
                            'rand_number' => $rand_number,
                        ];
                    }
                }
            }
        }
        if (empty($words_array)) {
            return;
        }
        
        $json_data = wp_json_encode($words_array);

        if ($order_by === 'random') {
            shuffle($words_array);
        }
        ?>
        <div class="<?php echo esc_attr($id); ?> ultimate-tag-cloud-container style-<?php echo esc_attr($tag_style); ?> <?php echo esc_attr($ribbon_style); ?>">
            <div class="ultimate-tag-cloud-words <?php echo esc_attr($tag_style); ?>">
                <?php foreach ($words_array as $word) :
                    if ($settings['random_color'] === 'yes') {
                        $cssVarColors = '--colorNormal: '.$word['rand_color'].'; --colorHigh: '.$word['rand_color_high'].';';
                    }
                    if ($settings['random_size'] === 'yes') {
                        $cssVarNumber = '--randSize: '.$word['rand_number'].'px;';
                    }
                ?>
                    <div class="tag-word-wrap <?php echo esc_attr($randColorCls); ?> <?php echo esc_attr($randSizeCls); ?>" style="<?php echo esc_attr($cssVarColors); ?> <?php echo esc_attr($cssVarNumber); ?>">
                        <a href="<?php echo esc_url($word['link']); ?>" class="ultimate-tag-cloud-word" title="<?php echo esc_attr($word['name']); ?>">
                            <?php echo esc_html($word['name']); ?> <?php if ($settings['show_count'] === 'yes') { ?> <span class="tag-count">(<?php echo esc_html($word['count']); ?>)</span> <?php } ?>
                        </a>
                    </div>
                <?php endforeach;?>
            </div>
        </div>
        <?php
    }

    /**
     * Get Random Number
     */
    private function get_random_number($min, $max) {
        return wp_rand($min, $max);
    }

    /**
     * Get Random Color
     */
    private function get_random_color() {
        $settings = $this->get_settings_for_display();
        $custom_colors_input = $settings['random_color_options'];

        $custom_colors = [];
        if (!empty($custom_colors_input)) {
            $hex_colors = array_map('trim', explode(',', $custom_colors_input));

            foreach ($hex_colors as $hex) {
                if (preg_match('/^#?[a-fA-F0-9]{6}$/', $hex)) {
                    $custom_colors[] = $this->hex_to_rgb($hex);
                }
            }
        }
        if (!empty($custom_colors)) {
            return $custom_colors[array_rand($custom_colors)];
        }

        $r = wp_rand(80, 220);
        $g = wp_rand(80, 220);
        $b = wp_rand(80, 220);
    
        return "$r, $g, $b";
    }
    private function hex_to_rgb($hex) {
        $hex = ltrim($hex, '#');
    
        if (strlen($hex) === 6) {
            list($r, $g, $b) = sscanf($hex, "%02x%02x%02x");
            return "$r, $g, $b";
        }
    
        return "0, 0, 0";
    }
    private function make_color_high($rgb) {
        list($r, $g, $b) = explode(', ', $rgb);
        $r = min(255, intval($r * 0.6));
        $g = min(255, intval($g * 0.6));
        $b = min(255, intval($b * 0.6));
        return "$r, $g, $b";
    }
}