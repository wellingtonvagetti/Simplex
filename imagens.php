<?php

class gf_cartesiano {
	
	public $_x_size_;
	public $_y_size_;
	
	public $_x0_;
	public $_y0_;
	
	public $_xi_;
	public $_xf_;
	public $_x_axis_;
	
	public $_yi_;
	public $_yf_;
	public $_y_axis_;
	
	public $image;
	
	public $white;
	public $black;
	public $red;
	public $blue;
	public $yellow;
	
	public $scale;
	public function __construct(&$x_axis, &$y_axis)
	
	{
		
		$this->_x_size_ = 480; // 480;
		$this->_y_size_ = 480; // 480;
		
		$this->_x0_ = 30;
		$this->_y0_ = $this->_y_size_ - 30;
		
		$this->_xi_ = 10;
		$this->_xf_ = $this->_x_size_ - $this->_xi_;
		$this->_x_axis_ = $this->_xf_ - $this->_x0_ - 10;
		$x_axis = $this->_x_axis_;
		
		$this->_yi_ = 10;
		$this->_yf_ = $this->_y_size_ - $this->_yi_;
		$this->_y_axis_ = $this->_yf_ - $this->_y0_ - 10;
		$y_axis = $this->_y_axis_;
		
		$this->image = imagecreatetruecolor($this->_x_size_, $this->_y_size_);
		
		$this->white = ImageColorAllocate($this->image, 255, 255, 255);
		$this->black = ImageColorAllocate($this->image, 0, 0, 0);
		
		$this->red = ImageColorAllocate($this->image, 127, 0, 0);
		$this->green = ImageColorAllocate($this->image, 0, 255, 0);
		$this->blue = ImageColorAllocate($this->image, 0, 0, 255);
		$this->magenta = ImageColorAllocate($this->image, 255, 0, 255);
		$this->yellow = ImageColorAllocate($this->image, 255, 255, 0);
		$this->darkgreen = ImageColorAllocate($this->image, 0, 100, 0);
		
		$this->darkorange = ImageColorAllocate($this->image, 255, 63, 63);
		
		$this->scale = 1;
		
		
	}
	public function fq_col($col)
	
	{
		$tile = imageCreateFromPNG('images/regione_amm.png');
		if ($tile == false) { die ('Unable to open image'); }
		imageSetTile ($this->image, $tile);
		imagefilledrectangle($this->image,  0,  0, $this->_x_size_, $this->_y_size_, $this->white);
		imagefilledrectangle($this->image, 30, 20, $this->_x_size_ -20, $this->_x_size_ - 30, IMG_COLOR_TILED);
	}
	public function assi($vertici, $scale)
	
	{
		
		imageline($this->image, $this->_xi_, $this->_y0_, $this->_xf_, $this->_y0_, $this->black);
		
		imageline($this->image, $this->_xf_ - 10, $this->_y0_ - 10, $this->_xf_, $this->_y0_, $this->black);
		imageline($this->image, $this->_xf_ - 10, $this->_y0_ + 10, $this->_xf_, $this->_y0_, $this->black);
		
		imagechar($this->image, 3, $this->_x0_ - 25, $this->_y0_ + 10, "O", $this->black);
		
		imagechar($this->image, 3, $this->_xf_ - 10, $this->_y0_ + 10, "x", $this->black);
		imagechar($this->image, 2, $this->_xf_, $this->_y0_ + 15, "1", $this->black);
		for ($i = 0; $i < count($vertici); $i+= 2) if ($vertici[$i] > 0) {
			$x = $this->map_x($vertici[$i], $scale);
			imageline($this->image, $x, $this->_y0_, $x, $this->_y0_ + 5, $this->black);
			if (abs(floor($vertici[$i]) - $vertici[$i]) <= 0.00001) $lettere = sprintf("%d", $vertici[$i]);
			else $lettere = sprintf("%.2f", $vertici[$i]);
			imagestring($this->image, 3, $x - 10, $this->_y0_ + 10, $lettere, $this->black);
		}
		
		imageline($this->image, $this->_x0_, $this->_yi_, $this->_x0_, $this->_yf_, $this->black);
		
		imageline($this->image, $this->_x0_ - 10, $this->_yi_ + 10, $this->_x0_, $this->_yi_, $this->black);
		imageline($this->image, $this->_x0_ + 10, $this->_yi_ + 10, $this->_x0_, $this->_yi_, $this->black);
		
		imagechar($this->image, 3, $this->_x0_ - 25, $this->_yi_ + 5, "x", $this->black);
		imagechar($this->image, 2, $this->_x0_ - 15, $this->_yi_ + 10, "2", $this->black);
		for ($i = 1; $i < count($vertici); $i+= 2) if ($vertici[$i] > 0) {
			$y = $this->map_y($vertici[$i], $scale);
			imageline($this->image, $this->_x0_ - 5, $y, $this->_x0_, $y, $this->black);
			if (abs(floor($vertici[$i]) - $vertici[$i]) <= 0.00001) $lettere = sprintf("%d", $vertici[$i]);
			else $lettere = sprintf("%.2f", $vertici[$i]);
			imagestring($this->image, 3, $this->_x0_ - 30, $y, $lettere, $this->black);
		}
	}
	public function map_x($vx, $scale)
	 {
		return $this->_x0_ + round($scale * $vx);
	}
	public function map_y($vy, $scale)
	 {
		return $this->_y0_ - round($scale * $vy);
	}
	public function ruota($x, $y, &$xr, &$yr, $alpha)
	
	{
		$xr = $x * cos($alpha) - $y * sin($alpha);
		$yr = $y * cos($alpha) + $x * sin($alpha);
	}
	public function line($scale, $vxi, $vyi, $vxf, $vyf, $col)
	 {
		
		$xi = $this->map_x($vxi, $scale);
		$xf = $this->map_x($vxf, $scale);
		$yi = $this->map_y($vyi, $scale);
		$yf = $this->map_y($vyf, $scale);
		
		imageline($this->image, $xi, $yi, $xf, $yf, $col);
		imageline($this->image, $xi-1, $yi, $xf-1, $yf, $col);
		imageline($this->image, $xi+1, $yi, $xf+1, $yf, $col);
		imageline($this->image, $xi, $yi-1, $xf, $yf-1, $col);
		imageline($this->image, $xi, $yi+1, $xf, $yf+1, $col);
	}
	public function freccia($scale, $vxi, $vyi, $vxf, $vyf, $col)
	{
		
		$xr = 0; $yr = 0; $xs = 0; $ys = 0;
		
		$xi = $this->map_x($vxi, $scale);
		$xf = $this->map_x($vxf, $scale);
		$yi = $this->map_y($vyi, $scale);
		$yf = $this->map_y($vyf, $scale);
		
		$x = $xf - $xi;
		$y = - ($yf - $yi);
		$modulo = sqrt($x * $x + $y * $y);
		
		if (!$modulo) return 0;
		$sin_ = $y / $modulo;
		$cos_ = $x / $modulo;
		
		
		$this->ruota(-10 * $cos_, -10 * $sin_, $xr, $yr, M_PI / 6);
		$xr+= $xf;
		$yr = $yf - $yr;
		
		$this->ruota(-10 * $cos_, -10 * $sin_, $xs, $ys, 11 * M_PI / 6);
		$xs+= $xf;
		$ys = $yf - $ys;
		
		imageline($this->image, $xi, $yi, $xf, $yf, $col);
		imageline($this->image, $xi-1, $yi, $xf-1, $yf, $col);
		imageline($this->image, $xi+1, $yi, $xf+1, $yf, $col);
		imageline($this->image, $xi, $yi-1, $xf, $yf-1, $col);
		imageline($this->image, $xi, $yi+1, $xf+1, $yf, $col);
		imagefilledpolygon($this->image, array($xr, $yr, $xs, $ys, $xf, $yf), 3, $col);
		
		
		
	}
	public function cerchio($scale, $vx0, $vy0, $l, $col)
	{
		$x0 = $this->map_x($vx0, $scale);
		$y0 = $this->map_y($vy0, $scale);
		$l2 = round($l / 2);
		
		imagefilledrectangle($this->image, $x0 - 3*$l2/2, $y0 - 3*$l2/2, $x0 + 3*$l2/2, $y0 + 3*$l2/2, $col);
	}
	public function ping($name)
	
	{
		imagepng($this->image, $name);
	}
	public function plotta_disequazione($ax, $ay, $b, $lge, $scale, $col)
	{ 
		
		$max = $this->_x_axis_ / $scale;
		$p0x = 0;
		$p0y = 0; 
		$p1x = $max;
		$p1y = 0; 
		$p2x = $max;
		$p2y = $max; 
		$p3x = 0;
		$p3y = $max; 
		$p4x = 0;
		$p4y = 0;
		
		if ($ax == 0) { 
			if ($ay == 0) return 0;
			$vx0 = 0;
			$vx1 = $max;
			$vy0 = $b / $ay;
			$vy1 = $vy0;
			
			if (!strcmp($lge, "=")) {
				$this->line($scale, $vx0, $vy0, $vx1, $vy1, $col);
				return 0;
			}
			
			if (strstr($lge, ">")) if ($ay > 0) $cancella_sotto = true;
			else $cancella_sotto = false;
			else if ($ay > 0) $cancella_sotto = false;
			else $cancella_sotto = true;
			if ($cancella_sotto) {
				if ($vy0 > 0) imagefilledrectangle($this->image, $this->map_x(0, $scale), $this->map_y($vy0, $scale), $this->map_x($max, $scale), $this->map_y(0, $scale), $col);
			} else if ($vy0 > 0) imagefilledrectangle($this->image, $this->map_x(0, $scale), $this->map_y($max, $scale), $this->map_x($max, $scale), $this->map_y($vy0, $scale), $col);
			else imagefilledrectangle($this->image, $this->map_x(0, $scale), $this->map_y($max, $scale), $this->map_x($max, $scale), $this->map_y(0, $scale), $col);
			return 0;
		}
		if ($ay) {
			$m = - $ax / $ay;
			$q = $b / $ay;
		} else { 
			$vx0 = $b / $ax;
			$vx1 = $vx0;
			$vy0 = 0;
			$vy1 = $max;
			
			if (!strcmp($lge, "=")) {
				$this->line($scale, $vx0, $vy0, $vx1, $vy1, $col);
				return 0;
			}
			
			if (strstr($lge, ">")) if ($ax > 0) $cancella_sx = true;
			else $cancella_sx = false;
			else if ($ax > 0) $cancella_sx = false;
			else $cancella_sx = true;
			if ($cancella_sx) {
				if ($vx0 > 0) imagefilledrectangle($this->image, $this->map_x(0, $scale), $this->map_y($max, $scale), $this->map_x($vx0, $scale), $this->map_y(0, $scale), $col);
			} else if ($vx0 > 0) imagefilledrectangle($this->image, $this->map_x($vx0, $scale), $this->map_y($max, $scale), $this->map_x($max, $scale), $this->map_y(0, $scale), $col);
			else imagefilledrectangle($this->image, $this->map_x(0, $scale), $this->map_y($max, $scale), $this->map_x($max, $scale), $this->map_y(0, $scale), $col);
			return 0;
		}
		
		if ($m >= 0) if ($q >= 0) {
			$vx0 = 0;
			$vy0 = $q;
			if (($vy1 = $m * $max + $q) < $max) {
				$vx1 = $max;
			} else {
				$vy1 = $max;
				$vx1 = ($max - $q) / $m;
			}
		} else {
			$vx0 = $b / $ax;
			$vy0 = 0;
			if (($vy1 = $m * $max + $q) < $max) {
				$vx1 = $max;
			} else {
				$vy1 = $max;
				$vx1 = ($max - $q) / $m;
			}
		} else { 
			if ($q >= 0) {
				$vx0 = 0;
				$vy0 = $q;
				if (($vy1 = $m * $max + $q) > 0) {
					$vx1 = $max;
				} else {
					$vy1 = 0;
					$vx1 = $b / $ax;
				}
			}
		}
		
		if (!strcmp($lge, "=")) {
			$this->line($scale, $vx0, $vy0, $vx1, $vy1, $col);
			return 0;
		}
		
		if (strstr($lge, ">")) if ($ay > 0) $cancella_sotto = true;
		else $cancella_sotto = false;
		else if ($ay > 0) $cancella_sotto = false;
		else $cancella_sotto = true;
		
		if ($m >= 0) 
		if ($q >= 0) { 
			if (!$cancella_sotto) { 
				if ($vx1 == $max) $array = array($vx0, $vy0, $vx1, $vy1, $p2x, $p2y, $p3x, $p3y);
				else $array = array($vx0, $vy0, $vx1, $vy1, $p3x, $p3y);
			} else { 
				if ($vx1 == $max) $array = array($vx0, $vy0, $vx1, $vy1, $p1x, $p1y, $p0x, $p0y);
				else $array = array($vx0, $vy0, $vx1, $vy1, $p2x, $p2y, $p1x, $p1y, $p0x, $p0y);
			}
		} else { 
			if (!$cancella_sotto) { 
				if ($vx1 == $max) $array = array($vx0, $vy0, $vx1, $vy1, $p2x, $p2y, $p3x, $p3y, $p0x, $p0y);
				else $array = array($vx0, $vy0, $vx1, $vy1, $p3x, $p3y, $p0x, $p0y);
			} else { 
				if ($vx1 == $max) $array = array($vx0, $vy0, $vx1, $vy1, $p1x, $p1y);
				else $array = array($vx0, $vy0, $vx1, $vy1, $p2x, $p2y, $p1x, $p1y);
			}
		} else
		
		if ($q >= 0) { 
			if (!$cancella_sotto) { 
				if ($vx1 == $max) $array = array($vx0, $vy0, $vx1, $vy1, $p2x, $p2y, $p3x, $p3y);
				else $array = array($vx0, $vy0, $vx1, $vy1, $p1x, $p1y, $p2x, $p2y, $p3x, $p3y);
			} else { 
				if ($vx1 == $max) $array = array($vx0, $vy0, $vx1, $vy1, $p1x, $p1y, $p0x, $p0y);
				else $array = array($vx0, $vy0, $vx1, $vy1, $p0x, $p0y);
			}
		} else { 
			if (!$cancella_sotto) { 
				if ($vx1 == $max) $array = array($p0x, $p0y, $p1x, $p1y, $p2x, $p2y, $p3x, $p3y, $p4x, $p4y);
			}
		}
		if (isset($array)) {
			for ($i = 0; $i < count($array);) {
				$array1[$i] = $this->map_x($array[$i], $scale);
				$array1[$i + 1] = $this->map_y($array[$i + 1], $scale);
				
				$i+= 2;
			}
			imagefilledpolygon($this->image, $array1, count($array1) / 2, $col);
			unset($array);
			unset($array1);
		}
	}
}
class grafico extends gf_cartesiano {
	public $basename;
	public $middlename;
	public $extension = ".png";
	public $vertici;
	public $grad_x;
	public $grad_y;
	public function __construct($_a, $_b, $_c, $lge, $cambia_segno_gradiente, $name) {
		$asse_x = 0;
		$asse_y = 0;
		parent::__construct($asse_x, $asse_y);
		
		for ($i = 1; $i < count($_b) + 1; $i++) {
			$b[$i] = $_b[$i]->value();
			for ($j = 1; $j < 3; $j++) $a[$i][$j] = $_a[$i][$j]->value();
		}
		for ($j = 1; $j < 3; $j++) $c[$j] = $_c[$j]->value();
		if ($cambia_segno_gradiente) {
			$this->grad_x = - $c[1];
			$this->grad_y = - $c[2];
		} else {
			$this->grad_x = $c[1];
			$this->grad_y = $c[2];
		}
		$this->basename = $name;
		
		$this->fq_col($this->yellow);
		$vertici = array();
		$this->trova_vertici($a, $b, $vertici);
		$max = $this->cerca_max($vertici) * 1.1;
		$this->scale = $asse_x / $max;
		for ($i = 1; $i < count($b) + 1; $i++) {
			$this->plotta_disequazione($a[$i][1], $a[$i][2], $b[$i], $lge[$i], $this->scale, $this->white);
			
			
		}
		for ($i = 1; $i < count($b) + 1; $i++) $this->plotta_disequazione($a[$i][1], $a[$i][2], $b[$i], "=", $this->scale, $this->blue);
		$this->gradiente();
		$this->assi($vertici, $this->scale);
		$name = $this->basename . $this->extension;
		$this->ping($name);
		return $this->image;
	}
	public function gradiente() {
		$this->freccia($this->scale, 0, 0, $this->grad_x, $this->grad_y, $this->magenta);
	}
	public function passo($xi, $yi, $xf, $yf, $passo, &$name) {
		$name = $this->basename . $passo;
		
		$this->plotta_disequazione($this->grad_x, $this->grad_y, ($xf * $this->grad_x + $yf * $this->grad_y), "=", $this->scale, $this->darkgreen);
		$this->cerchio($this->scale, $xf, $yf, 6, $this->red);
		$this->freccia($this->scale, $xi, $yi, $xf, $yf, $this->darkorange);
		
		$this->ping($name . ".png");
		$this->gradiente();
		$this->cerchio($this->scale, $xf, $yf, 6, $this->red);
	}
	public function cerca_max(&$vertici) {
		$max = $vertici[0];
		for ($i = 1; $i < count($vertici); $i++) if ($vertici[$i] > $max) $max = $vertici[$i];
		
		return $max;
	}
	public function trova_vertici($a, $b, &$vertici) {
		
		$m = count($b);
		$vertici = array_fill(0, ($m * $m + 3 * $m) / 2 + 1, 0);
		
		for ($k = 0, $i = 1; $i < $m + 1; $i++) for ($j = $i + 1; $j < $m + 1; $j++) {
			
			if ($a[$i][1] * $a[$j][2] != $a[$j][1] * $a[$i][2]) $vertici[$k] = ($a[$j][2] * $b[$i] - $a[$i][2] * $b[$j]) / ($a[$i][1] * $a[$j][2] - $a[$j][1] * $a[$i][2]);
			else $vertici[$k] = 0;
			$k++;
			
			if ($a[$i][2] != 0) $vertici[$k] = ($b[$i] - $a[$i][1] * $vertici[$k - 1]) / $a[$i][2];
			else $vertici[$k] = 0;
			$k++;
			
			
		}
		
		for ($i = 1; $i < $m + 1; $i++) {
			if (!isset($a[$i][1]) || $a[$i][1] != 0) {
				$vertici[$k++] = $b[$i] / $a[$i][1];
				$vertici[$k++] = 0;
			} 
			
			
			if (!isset($a[$i][2]) || $a[$i][2] != 0) {
				$vertici[$k++] = 0;
				$vertici[$k++] = $b[$i] / $a[$i][2];
			} 
			
			
			
		}
	}
}
?>
