<?php
/**
 * 系统的通用分页栏控件，接收接单的参数，生成出需要的分页栏HTML内容
 * 需要使用到的CSS样式表：
	pager_unabled 不可用的按钮（span）
	pager_cur 当前的按钮(span)
	pager_cur_select 当前选择型按钮（span）
	pager_more_select 选择型分页按钮(div)
	pager_input_text 文本输入框(input type="text")
	pager_input_button 表单按钮（input type="button"）
 * 
 *
 */

namespace common;

class SysPager {

	/** 总记录数*/
	private $_total_num = 0;
	/** 当前页码 */
	public $_page_index = 1;
	/** 每页记录数 */
	public $_page_size = 10;
	/** 初始索引 */
	public $_first_index = 1;
	/** 结束索引，需要进行设置 */
	public $_end_index = 10;
	/** 分页按钮显示的个数，如：“1 2 3 4 5 6 7 8 9 10 ...” */
	public $page_bar_size = 10;
	/** 当前页码的对齐方式，0：居中对齐；1：左对齐；2：右对齐； */
	public $cur_page_align = 0;
	/** 当前页码的标签名称，默认是 span，可以为空（如果为空，则css_cur_select无效） */
	public $cur_page_tag = '';
	/** 不可用页码的标签名称，默认是 span，可以为空（如果为空，则css_unabled无效） */
	public $unabled_page_tag = '';
	/** 是否显示不可用的分页，默认不显示 */
	public $show_unabled = 0;
	/** 是否显示上一页、下一页，默认显示 */
	public $show_prev_next = 1;
	/** 是否显示首页、末页，默认显示 */
	public $show_first_end = 1;
	/** 总页数少于默认显示页码数时是否显示首页、末页，默认不显示。例：只显示10个分页按钮，当总页数小于10页的时候，不显示首页、末页 */
	public $show_litter_size_first_end = 0;
	/** 分页显示模板，0：平铺；1：下拉框；2：<td>包装后输出； */
	public $page_template = 0;
	/** 分页栏的页面ID */
	public $id = "pager";
	/** 链接的目标位置 */
	public $target = '';
	/** 分页显示格式，可以使用的变量有：${PAGE}和${TOTAL_PAGE} */
	public $bar_html = '${PAGE}';
	/** 当前分页的显示格式，可以使用的变量有：${PAGE}和${TOTAL_PAGE} */
	public $cur_bar_html = '${PAGE}';
	/** 首页的文字描述 */
	public $bar_first_text = '首页';
	/** 上一页的文字描述 */
	public $bar_prev_text = '«';
	/** 下一页的文字描述 */
	public $bar_next_text = '»';
	/** 末页的文字描述 */
	public $bar_end_text = '尾页';
	/** 分页栏整体的显示格式，可以使用的变量有：${STR_PAGER}，${CUR_PAGE}，${TOTAL_NUM}，${TOTAL_PAGE}，${FIRST_INDEX}，${PAGE_SIZE}，${END_INDEX}，如：共${TOTAL_NUM}个，每页显示${PAGE_SIZE}个，当前从${FIRST_INDEX}到${END_INDEX}个，共${TOTAL_PAGE}页，${STR_PAGER} */
	public $page_html = '${STR_PAGER}';
	/** 分页栏只有一页的显示格式，可以使用的变量有：${STR_PAGER}，${CUR_PAGE}，${TOTAL_NUM}，${TOTAL_PAGE}，${FIRST_INDEX}，${PAGE_SIZE}，${END_INDEX} */
	public $page_one_html = '';
	/** 分页栏没有记录时显示文字 */
	public $page_none_text = '';
	/** 网页的链接地址格式，默认是 javascript:showPagerPage(${PAGE}, "$this->id")，常用的格式：?page=${PAGE}，可以使用的变量有：${PAGE}和${PAGE_SIZE} */
	public $url_html = '';
	/** 不可用的按钮（span） */
	public $css_unabled = 'disabled';
	/** 当前的按钮的样式表名称(span) */
	public $css_cur = 'active';
	/** 当前选择型按钮的样式表名称（span） */
	public $css_cur_select = 'pager_cur_select';
	/** 选择型分页按钮的样式表名称(div) */
	public $css_more_select = 'pager_more_select';
	/** 文本输入框的样式表名称(input type="text") */
	public $css_input_text = 'pager_input_text';
	/** 表单按钮的样式表名称（input type="button"） */
	public $css_input_button = 'pager_input_button';
	/** 总页数 */
	public $total_page = 0;
    /** 分页连接的外围标签，默认是 li 标签 */
    public $link_tag = 'li';

	/**
	 * 分页控件的默认构造方法
	 *
	 * @param Integer $total_num 总记录数
	 * @param Integer $page_index 当前页码
	 * @param Integer $page_size 每页记录数
	 * @return SysPager
	 */
	public function __construct($total_num, $page_index = 1, $page_size=10)
	{
		if ($total_num > 0)
		{
			$this->_total_num = $total_num;
		}
		else
		{
			$this->_total_num = 0;
		}
		if ($page_index < 1)
		{
			$this->_page_index = 1;
		}
		else
		{
			$this->_page_index = $page_index;
		}
		if ($page_size < 1)
		{
			$this->_page_size = 10;
		}
		else 
		{
			$this->_page_size = $page_size;
		}
		$this->_first_index = ($this->_page_index - 1) * $this->_page_size + 1;
		$this->_end_index = $this->_page_index * $this->_page_size;
		if ($this->_first_index < 1)
		{
			$this->_first_index = 1;
		}
		if ($this->_end_index > $total_num)
		{
			$this->_end_index = $total_num;
		}
		$this->total_page = $this->getPageNum();
		if ($this->_page_index > $this->total_page)
		{
			$this->_page_index = $this->total_page;
		}
	}
	
	/**
	 * 生成分页栏的HTML，注意：需要特殊的显示格式还必须设置上面的相应属性，否则只能以默认的方式输出分页HTML
	 *
	 * @return 分页的HTML
	 */
	public function createHtml()
	{
		if ($this->_total_num == 0 || $this->total_page == 0)
		{	// 没有任何记录
			return $this->page_none_text;
		}
		if ($this->total_page == 0)
		{
			return $this->page_none_text;
		}
		else if ($this->total_page == 1)
		{
			$html = $this->page_one_html;
		}
		else if ($this->total_page > 1)
		{
			$html = $this->page_html;
		}
		if (empty($html))
		{	// 输出格式为空的时候，直接输出
			return $html;
		}
		if ($this->page_template == 2)
		{
			$this->link_tag = 'td';
		}
		if (strpos($html, '${STR_PAGER}') !== false)
		{	// 只有在需要显示的时候才会去计算分页栏的信息
			$str_pager = $this->createPagerHtml();
			$html = str_replace('${STR_PAGER}', $str_pager, $html);
		}
		$html = str_replace('${CUR_PAGE}', $this->_page_index, $html);
		$html = str_replace('${TOTAL_NUM}', $this->_total_num, $html);
		$html = str_replace('${TOTAL_PAGE}', $this->total_page, $html);
		$html = str_replace('${FIRST_INDEX}', $this->_first_index, $html);
		$html = str_replace('${PAGE_SIZE}', $this->_page_size, $html);
		$html = str_replace('${END_INDEX}', $this->_end_index, $html);
		return $html;
	}
	
	/**
	 * 计算出分页栏的总页数
	 *
	 * @return Integer
	 */
	public function getPageNum()
	{
		if ($this->_total_num > $this->_page_size)
		{
			return  ceil($this->_total_num / $this->_page_size);
		}
		else if ($this->_total_num > 0)
		{
			return 1;
		}
		else 
		{
			return 0;
		}
	}
	
	/**
	 * 生成分页栏的HTML内容
	 *
	 */
	private function createPagerHtml()
	{
		$html = $this->createFirstEndHtml(1);	// 输出开始的按钮

		// 计算出分页栏中开始的页码和结束的页码
		$start_page = 1;				// 开始页码
		$end_page = $this->total_page;	// 结束页码
		$this->processPageIndex($start_page, $end_page);
		
		// 中间区域的分页显示
		if ($this->page_template == 1)
		{	// 下拉框的模板效果
			$html .= $this->createCurSelect();
		}
		else 
		{ // 循环输出中间的分页按钮
			for ($i = $start_page; $i <= $end_page; $i++)
			{
				if ($this->_page_index == $i)
				{
					$html .= $this->createCurLinkButton();
				}
				else 
				{
					$html .= $this->createLinkButton($i);
				}
			}
		}
		$html .= $this->createFirstEndHtml(2);	// 输出结束的按钮
		
		$html .= $this->createOtherHtml($start_page, $end_page);	// 创建其他的HTML内容，如：隐藏的FORM和相应的JS
		return $html;
	}
	
	/**
	 * 特定的分页栏按钮是否可用
	 *
	 * @param Integer $type 按钮的类型，1：首页；2：上一页；3：下一页；4：末页；
	 * @param Integer $this->total_page 总页数
	 * @return Boolean 是否可用
	 */
	private function enablePageBar($type)
	{
		if ($type == 1 || $type == 2)
		{	// 首页
			if ($this->_page_index > 1)
			{
				return true;
			}
		}
		else if ($type == 3 || $type == 4)
		{
			if ($this->_page_index < $this->total_page)
			{
				return true;
			}
		}
		return false;
	}
	
	/**
	 * 创建不可用的分页按钮
	 * @param String $text 文字
	 */
	private function createUnabledButton($text)
	{
		if ($this->unabled_page_tag)
		{
			return '<'.$this->unabled_page_tag.' class="'.$this->css_unabled.'">' . $text . '</'.$this->unabled_page_tag.'>';
		}
		else
		{
			return $text;
		}
	}

    private function getPageUrl($page) {
        if (empty($this->url_html))
        {
            $url = 'javascript:showPagerPage(' . $page . ', \'' . $this->id . '\')';
        }
        else
        {
            $url = str_replace('${PAGE}', $page, $this->url_html);
            $url = str_replace('${PAGE_SIZE}', $this->_page_size, $url);
        }
        return $url;
    }
	
	/**
	 * 创建分页的链接按钮
	 * @param $page 页码，-1：首页；-2：上一页；-3：下一页；-4：末页
	 * @return String
	 */
	private function createLinkButton($page)
	{
		if ($page === -1)
		{
			$text = $this->bar_first_text;
			$page = 1;
		}
		else if ($page === -2)
		{
			$text = $this->bar_prev_text;
			$page = $this->_page_index - 1;
		}
		else if ($page === -3)
		{
			$text = $this->bar_next_text;
			$page = $this->_page_index + 1;
		}
		else if ($page === -4)
		{
			$text = $this->bar_end_text;
			$page = $this->total_page;
		}
		else 
		{
			$text = str_replace('${PAGE}', $page, $this->bar_html);
			$text = str_replace('${TOTAL_PAGE}', $this->total_page, $text);
		}
		$url = $this->getPageUrl($page);
		return '<' . $this->link_tag . '><a href="' . $url . '"'.($this->target ? ' target="' . $this->target . '"' : '').'>' . $text . '</a></' . $this->link_tag . '>';
	}

	/**
	 * 创建当前分页栏的按钮
	 * 
	 * @return String
	 */
	private function createCurLinkButton()
	{
		$text = str_replace('${PAGE}', $this->_page_index, $this->cur_bar_html);
		$text = str_replace('${TOTAL_PAGE}', $this->total_page, $text);
        $url = $this->getPageNum($this->_page_index);
		if ($this->cur_page_tag == '' && $this->page_template != 1)
		{
            if ($this->css_cur) {
                return '<' . $this->link_tag . ' class="' . $this->css_cur . '"><a href="' . $url . '">' . $text . '</a></' . $this->link_tag . '>';
            } else {
                return '<' . $this->link_tag . '><a href="' . $url . '">' . $text . '</a></' . $this->link_tag . '>';
            }
		}
		else
		{
			if ($this->page_template == 1)
			{	// 下拉框显示的模板
				return '<span class="'.$this->css_cur_select.'">' . $text . '</span>';
			}
			else 
			{
				return '<' . $this->link_tag . '><'.$this->cur_page_tag.' class="'.$this->css_cur.'">' . $text . '</'.$this->cur_page_tag.'></'.$this->link_tag . '>';
			}
		}
	}
	
	/**
	 * 创建当前的分页按钮
	 * 
	 * @return String
	 */
	private function createCurSelect()
	{
		$text = str_replace('${PAGE}', $this->_page_index, $this->cur_bar_html);
		$text = str_replace('${TOTAL_PAGE}', $this->total_page, $text);
		return '<span class="'.$this->css_cur_select.'" onclick="if (typeof(showPagerSelect) != \'undefined\'){showPagerSelect(event, \'' . $this->id . '\');}">'.$text.'</span>';
	}
	
	/**
	 * 创建分页栏的开始与结束部分的HTML
	 *
	 * @param Integer $type 类型，1：开始，2：结束
	 * @return String
	 */
	private function createFirstEndHtml($type)
	{
		$html = '';
		if ($type == 1)
		{
			if ($this->show_first_end && ($this->show_litter_size_first_end || $this->total_page > $this->page_bar_size))
			{	// 首页，需要显示“首末页”并且（当总页数偏少时也显示“首末页”或者总页数超过了显示页数）
				if (!$this->enablePageBar(1) && $this->show_unabled)
				{
					$html .= $this->createUnabledButton($this->bar_first_text);
				}
				else if ($this->enablePageBar(1))
				{
					$html .= $this->createLinkButton(-1);
				}
			}
			if ($this->show_prev_next)
			{	// 上一页
				if (!$this->enablePageBar(2) && $this->show_unabled)
				{
					$html .= $this->createUnabledButton($this->bar_prev_text);
				}
				else if ($this->enablePageBar(2))
				{
					$html .= $this->createLinkButton(-2);
				}
			}
		}
		else 
		{
			if ($this->show_prev_next)
			{	// 下一页
				if (!$this->enablePageBar(3) && $this->show_unabled)
				{
					$html .= $this->createUnabledButton($this->bar_next_text);
				}
				else if ($this->enablePageBar(3))
				{
					$html .= $this->createLinkButton(-3);
				}
			}
			if ($this->show_first_end && ($this->show_litter_size_first_end || $this->total_page > $this->page_bar_size))
			{	// 末页
				if (!$this->enablePageBar(4) && $this->show_unabled)
				{
					$html .= $this->createUnabledButton($this->bar_end_text);
				}
				else if ($this->enablePageBar(4))
				{
					$html .= $this->createLinkButton(-4);
				}
			}
		}
		return $html;
	}
		
	/**
	 * 创建分页栏的其余部分，如：隐藏的FORM和相应的JS
	 *
	 * @param Integer $start_page 开始页码
	 * @param Integer $end_page 结束页码
	 * @return String
	 */
	private function createOtherHtml($start_page, $end_page)
	{
		$html = '';
		if ($this->page_template == 1)
		{	// 下拉框的模板效果
			$html .= '<div id="pager_'.$this->id.'" class="'.$this->css_more_select.'" ';
			$html .= 'onmouseover="document.getElementById(\'pager_'.$this->id.'\').style.display=\'block\'" onmouseout="document.getElementById(\'pager_'.$this->id.'\').style.display=\'none\'" style="display:none;">';
			for ($i = $start_page; $i <= $end_page; $i++)
			{
				if ($i == $this->_page_index)
				{
					$html .= '<div>'.$this->createCurLinkButton().'</div>';
				}
				else 
				{
					$html .= '<div>'.$this->createLinkButton($i).'</div>';
				}
			}
			$html .= '</div>';
		}
		return $html;
	}
	
	/**
	 * 重新计算记录的开始、结束索引值
	 *
	 */
	private function reCountIndex()
	{
		$this->_first_index = ($this->_page_index - 1) * $this->_page_size + 1; 
		if ($this->_end_index > $this->_total_num)
		{
			$this->_end_index = $this->_total_num;
		}
	}

	/**
	 * 处理当前需要显示的页码
	 *
	 * @param Integer $start_page 开始页码
	 * @param Integer $end_page 结束页码
	 */
	private function processPageIndex(&$start_page, &$end_page)
	{
		if ($this->page_bar_size < $this->total_page)
		{	// 最大分页按钮数小于总页数的时候才需要处理
			if ($this->cur_page_align == 0)
			{	// 居中对齐
				if ($this->_page_index > ceil($this->page_bar_size / 2))
				{
					$start_page = $this->_page_index - ceil($this->page_bar_size / 2) + 1;
				}
				$end_page = $start_page + $this->page_bar_size - 1;
				if ($end_page > $this->total_page)
				{
					$end_page = $this->total_page;
					$start_page = $end_page - $this->page_bar_size + 1;
				}
			}
			else if ($this->cur_page_align == 1)
			{	// 左对齐
				$start_page = $this->_page_index;
				$end_page = $start_page + $this->page_bar_size - 1;
				if ($end_page > $this->total_page)
				{
					$end_page = $this->total_page;
					$start_page = $this->total_page - $this->page_bar_size + 1;
				}
			}
			else if ($this->cur_page_align == 2)
			{	// 右对齐
				$end_page = $this->_page_index;
				$start_page = $end_page - $this->page_bar_size + 1;
				if ($start_page < 1)
				{
					$start_page = 1;
					$end_page = $start_page + $this->page_bar_size - 1;
				}
			}
			$this->reCountIndex();
		}
	}
	
}
