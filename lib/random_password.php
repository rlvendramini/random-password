<?php
	/**
	 * Class that generates random passwords
	 * @author Renan Luiz Vendramini <renan@alboompro.com>
	 * @version v2.0
	 */
	class RandomPassword {

		const STR_LENGTH = 20;

		static private function characters($picked_groups = []) {
			$groups = [
				'lowercase' => range('a', 'z'),
				'uppercase' => range('A', 'Z'),
				'numbers' => range(0, 9),
				'special' => ['!','@','#','$','%','&','*','(',')','-','_','=','+','.',':',';','\\','\/',']','[','}','{','~','^',',','|']
			];

			return empty($picked_groups) ? $groups : array_filter($groups, function($key) use ($picked_groups) {
				return in_array($key, $picked_groups);
			}, ARRAY_FILTER_USE_KEY);
		}

		static private function shuffle_character_groups(&$characters) {
			foreach($characters as $key => $value) {
				shuffle($characters[$key]);
			}
			
			shuffle($characters);

			return $characters;
		}

		static private function pick_character_group($characters) {
			return array_keys($characters)[rand(0, count($characters) - 1)];
		}

		static private function pick_char($characters) {
			$group = self::pick_character_group($characters);
			$group_limit = count($characters[$group]);

			return $characters[$group][rand(0, $group_limit - 1)];
		}

		/**
		 * Generates a random password based in your configurations, or default (length 12, all sorts of characters)
		 * If you defined a sort of custom characters, they will automatically be considered in characters mix
		 * @return [string] [returns a generated password]
		 */
		static public function generate($givenLength = null, $groups = []) {
			$characters = self::characters($groups);
			$str;

			for($i = $givenLength ?: self::STR_LENGTH; $i > 0; $i--) {				
				$str .= self::pick_char($characters);
				self::shuffle_character_groups($characters);
			}

			return $str;
		}
	}
