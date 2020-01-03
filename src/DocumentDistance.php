<?php

namespace AdrianoFerreira\DD;

class DocumentDistance {

	/**
	 * @param string $file1
	 * @param string $file2
	 * @param bool   $f1Remote
	 * @param bool   $f2Remote
	 *
	 * @return int
	 */
	public function getFilesPercentageDistance( $file1, $file2, $f1Remote = false, $f2Remote = false ) {
		list( $f1Data, $f2Data ) = $this->getFilesData( $file1, $file2, $f1Remote, $f2Remote );

		return $this->getPercentageDistance( $f1Data, $f2Data );
	}

	/**
	 * @param string $file1
	 * @param string $file2
	 * @param bool   $f1Remote
	 * @param bool   $f2Remote
	 *
	 * @return float
	 */
	public function getFilesArcSize( $file1, $file2, $f1Remote = false, $f2Remote = false ) {
		list( $f1Data, $f2Data ) = $this->getFilesData( $file1, $file2, $f1Remote, $f2Remote );

		return $this->getArcSize( $f1Data, $f2Data );
	}

	/**
	 * @param string $text1
	 * @param string $text2
	 *
	 * @return int
	 */
	public function getPercentageDistance( $text1, $text2 ) {
		$result = $this->getDistance( $text1, $text2 );

		return 100 - (int) ( ( $result * 100 ) / 1.57 );
	}

	/**
	 * @param string $text1
	 * @param string $text2
	 *
	 * @return float
	 */
	public function getArcSize( $text1, $text2 ) {
		return $this->getDistance( $text1, $text2 );
	}

	private function getFilesData( $file1, $file2, $f1Remote = false, $f2Remote = false ) {
		$f1 = __DIR__ . '/../' . $file1;
		$f2 = __DIR__ . '/../' . $file2;

		if ( $f1Remote ) {
			$f1 = $file1;
		}

		if ( $f2Remote ) {
			$f2 = $file2;
		}

		if ( ! $f1Remote && ! file_exists( $f1 ) ) {
			throw new \BadMethodCallException( 'File 1 not found' );
		}

		if ( ! $f2Remote && ! file_exists( $f2 ) ) {
			throw new \BadMethodCallException( 'File 2 not found' );
		}

		$file1Handle = fopen( $f1, 'r' );
		$file2Handle = fopen( $f2, 'r' );

		$f1Data = '';
		$f2Data = '';

		$buffer1 = fgets( $file1Handle, 4096 );
		$buffer2 = fgets( $file2Handle, 4096 );

		while ( $buffer1 || $buffer2 ) {
			$f1Data .= $buffer1;
			$f2Data .= $buffer2;

			$buffer1 = fgets( $file1Handle, 4096 );
			$buffer2 = fgets( $file2Handle, 4096 );
		}

		return [ $f1Data, $f2Data ];
	}

	private function getDistance( $text1, $text2 ) {
		$s1Frequency = $this->getFrequency( $text1 );
		$s2Frequency = $this->getFrequency( $text2 );

		$numerator   = $this->innerProduct( $s1Frequency, $s2Frequency );
		$denominator = sqrt( $this->innerProduct( $s1Frequency, $s1Frequency ) * $this->innerProduct( $s2Frequency, $s2Frequency ) );

		return acos( $numerator / $denominator );
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
		$len   = strlen( $s );

		for ( $i = 0; $i <= $len; $i ++ ) {
			if ( ! isset( $s[ $i ] ) ) {
				continue;
			}

			if ( $s[ $i ] === ' ' || $s[ $i ] === PHP_EOL || $i === strlen( $s ) ) {

				if ( ! isset( $table[ $temp ] ) ) {
					$table[ $temp ] = 0;
				}

				$table[ $temp ] ++;
				$temp = '';
				continue;
			}

			if ( in_array( $s[ $i ], [ '!', '.', ';', ',', ':', '?' ] ) ) {
				continue;
			}

			$temp .= $s[ $i ];
		}

		return $table;
	}
}