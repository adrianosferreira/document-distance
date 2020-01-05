<?php

namespace AdrianoFerreira\DD;

class DocumentDistance {

	const BUFFER_LENGTH = 4096;

	/**
	 * @param string $file1
	 * @param string $file2
	 *
	 * @return int
	 */
	public function getFilesPercentSimilarity( $file1, $file2 ) {
		list( $f1Data, $f2Data ) = $this->getFilesData( $file1, $file2 );

		return $this->getPercentSimilarity( $f1Data, $f2Data );
	}

	/**
	 * @param string $file1
	 * @param string $file2
	 *
	 * @return float
	 */
	public function getFilesArcSize( $file1, $file2 ) {
		list( $f1Data, $f2Data ) = $this->getFilesData( $file1, $file2 );

		return $this->getArcSize( $f1Data, $f2Data );
	}

	/**
	 * @param string $text1
	 * @param string $text2
	 *
	 * @return int
	 */
	public function getPercentSimilarity( $text1, $text2 ) {
		$arcSize = $this->getArcSize( $text1, $text2 );

		return 100 - (int) ( ( $arcSize * 100 ) / 1.57 );
	}

	/**
	 * @param string $text1
	 * @param string $text2
	 *
	 * @return float
	 */
	public function getArcSize( $text1, $text2 ) {
		$s1Frequency = $this->getFrequency( $text1 );
		$s2Frequency = $this->getFrequency( $text2 );

		$numerator   = $this->innerProduct( $s1Frequency, $s2Frequency );
		$denominator = sqrt( $this->innerProduct( $s1Frequency, $s1Frequency ) * $this->innerProduct( $s2Frequency, $s2Frequency ) );

		return acos( $numerator / $denominator );
	}

	/**
	 * @param string $file1
	 * @param string $file2
	 *
	 * @return array
	 */
	private function getFilesData( $file1, $file2 ) {
		if ( ! file_exists( $file1 ) ) {
			throw new \BadMethodCallException( 'File 1 not found' );
		}

		if ( ! file_exists( $file2 ) ) {
			throw new \BadMethodCallException( 'File 2 not found' );
		}

		$file1Handle = fopen( $file1, 'r' );
		$file2Handle = fopen( $file2, 'r' );

		$f1Data = '';
		$f2Data = '';

		$buffer1 = fgets( $file1Handle, self::BUFFER_LENGTH );
		$buffer2 = fgets( $file2Handle, self::BUFFER_LENGTH );

		while ( $buffer1 || $buffer2 ) {
			$f1Data .= $buffer1;
			$f2Data .= $buffer2;

			$buffer1 = fgets( $file1Handle, self::BUFFER_LENGTH );
			$buffer2 = fgets( $file2Handle, self::BUFFER_LENGTH );
		}

		return [ $f1Data, $f2Data ];
	}

	/**
	 * @param array $l1
	 * @param array $l2
	 *
	 * @return float|int
	 */
	private function innerProduct( array $l1, array $l2 ) {
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

	/**
	 * @param string $s
	 *
	 * @return array
	 */
	private function getFrequency( $s ) {
		$table = [];
		$temp  = '';
		$len   = strlen( $s );

		for ( $i = 0; $i <= $len; $i ++ ) {

			if ( $temp && ( ! isset( $s[ $i ] ) || $s[ $i ] === ' ' || $s[ $i ] === PHP_EOL || $i === strlen( $s ) ) ) {

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

			if ( $s[ $i ] !== ' ' ) {
				$temp .= $s[ $i ];
			}
		}

		return $table;
	}
}