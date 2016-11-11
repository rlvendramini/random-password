<?php
	/**
	 * Class that generates random passwords
	 * @author Renan Luiz Vendramini <renan@alboompro.com>
	 * @version v1.0.0
	 */
	class randomPassword {
		private $core;
		private $options;
		private $password;

		/**
		 * object instance with default configurations
		 * length 12
		 * all types of characters
		 */
		public function __construct(){
			$this->core['characters'] = [
				'lower' => range('a', 'z'),
				'upper' => range('A', 'Z'),
		        'num' => range(0, 9),
		        'sym' => ['!','@','#','$','%','&','*','(',')','-','_','=','+','.',':',';','\\','\/',']','[','}','{','~','^',',','|'],
		        'extra' => array()
			];
			$this->options['length'] = 12;
			$this->options['characters'] = array_keys($this->core['characters']);
		}

		/**
		 * Define your password's length
		 * @param [int] $length [password's max length]
		 */
		public function setPasswordLength($length){
			$this->options['length'] = $length;
			return $this;
		}

		/**
		 * Define your password's sort of characters
		 * @param array $characters [the characters types can be 'lower' (lowercase chars), 'upper' (uppercase chars), 'sym' (symbols) and 'num' (numbers)]
		 * Don't need to references 'extra' option in here, because it will be automatically considered if not empty
		 */
		public function setPasswordCharacters(array $characters){
			if(!empty($characters)){
				$this->options['characters'] = $characters;
			}
			return $this;
		}

		/**
		 * Define your custom sort of characters to arrange in generated password
		 * @param array $characters [array with your custom chars]
		 */
		public function setExtraCharacters(array $characters){
			$this->core['characters']['extra'] = $characters;
			return $this;
		}

		/**
		 * Remove defined extra characters
		 */
		public function unsetExtraCharacters(){
			unset($this->options['characters']['extra']);
			unset($this->core['characters']['extra']);
			return $this;
		}

		/**
		 * Generates a random password based in your configurations, or default (length 12, all sorts of characters)
		 * If you defined a sort of custom characters, they will automatically be considered in characters mix
		 * @return [string] [returns a generated password]
		 */
		public function generate(){
			if(!empty($this->core['characters']['extra'])){
				array_push($this->options['characters'], 'extra');
			}
			for($i = $this->options['length']; $i > 0; $i--){
				$typeLimit = rand(0, count($this->options['characters'])-1);
		        $type = $this->options['characters'][$typeLimit];
		        $limit = count($this->core['characters'][$type]);
		        $this->password .= $this->core['characters'][$type][rand(0, $limit-1)];
		        shuffle($this->core['characters']['sym']);
		        shuffle($this->core['characters']['num']);
		        shuffle($this->options['characters']);
		    }
		    return $this->password;
		}
	}
?>