<?php

class DocumentDistance {

	public function documentDistance( $s1, $s2 ) {
		$s1Frequency = $this->getFrequency( $s1 );
		$s2Frequency = $this->getFrequency( $s2 );

		$numerator   = $this->innerProduct( $s1Frequency, $s2Frequency );
		$denominator = sqrt( $this->innerProduct( $s1Frequency, $s1Frequency ) * $this->innerProduct( $s2Frequency, $s2Frequency ) );
		$result      = acos( $numerator / $denominator );

		$test = 1;
	}

	private function innerProduct( $l1, $l2 ) {
		$sum = 0.0;
		foreach ( $l1 as $key => $val ) {
			foreach ( $l2 as $key2 => $val2 ) {
				if ( $key === $key2 ) {
					$sum += $val * $val2;
				}
			}
		}

		return $sum;
	}

	private function getFrequency( $s ) {
		$table = [];
		$temp  = '';

		for ( $i = 0; $i <= strlen( $s ); $i ++ ) {
			if ( $s[ $i ] === ' ' || $i === strlen( $s ) ) {

				if ( ! isset( $table[ $temp ] ) ) {
					$table[ $temp ] = 0;
				}

				$table[ $temp ] ++;
				$temp = '';
				continue;
			}

			if ( $s[ $i ] === '!' ) {
				continue;
			}

			$temp .= $s[ $i ];
		}

		return $table;
	}
}