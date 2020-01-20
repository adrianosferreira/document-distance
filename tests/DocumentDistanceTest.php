<?php

namespace AdrianoFerreira\DD;

use org\bovigo\vfs\vfsStream;
use PHPUnit\Framework\TestCase;

class DocumentDistanceTest extends TestCase {

	/**
	 * @test
	 * @dataProvider dpFilesSameContent
	 *
	 * @param $content1
	 * @param $content2
	 */
	public function itChecksFilesSameContent( $content1, $content2 ) {

		vfsStream::setup( 'home' );

		$file  = vfsStream::url( 'home/test.txt' );
		$file2 = vfsStream::url( 'home/test2.txt' );

		file_put_contents( $file, $content1 );
		file_put_contents( $file2, $content2 );

		$dd = new File( $file, $file2 );
		$this->assertEquals( 100, $dd->getPercent() );
	}

	public function dpFilesSameContent() {
		return [
			[
				'The new contents of the file',
				'The new contents of the file'
			],
			[
				'The new contents of the file' . PHP_EOL . 'Test 123' . PHP_EOL . 'Test Test Test',
				'The new contents of the file' . PHP_EOL . 'Test 123' . PHP_EOL . 'Test Test Test',
			],
			[
				'The new contents! of the file' . PHP_EOL . 'Test. 123' . PHP_EOL . 'Test Test; Test',
				'The new contents! of the file' . PHP_EOL . 'Test. 123' . PHP_EOL . 'Test Test; Test',
			]
		];
	}

	/**
	 * @test
	 * @dataProvider dpFilesWithContentNotTotallyEqual
	 *
	 * @param $content1
	 * @param $content2
	 */
	public function itChecksFilesWithContentNotTotallyEqual( $content1, $content2 ) {

		vfsStream::setup( 'home' );

		$file  = vfsStream::url( 'home/test.txt' );
		$file2 = vfsStream::url( 'home/test2.txt' );

		file_put_contents( $file, $content1 );
		file_put_contents( $file2, $content2 );

		$dd = new File( $file, $file2 );
		$this->assertTrue( $dd->getPercent() < 100 );
	}

	public function dpFilesWithContentNotTotallyEqual() {
		return [
			[
				'The new contents of the file',
				'The new contents test 1 of the file'
			],
			[
				'The new contents a bbb of the file' . PHP_EOL . 'Test 123' . PHP_EOL . 'Test Test Test',
				'The new contents of the file' . PHP_EOL . 'Test 123' . PHP_EOL . 'Test Test Test',
			],
			[
				'The new contents! of the file' . PHP_EOL . 'Test. 123' . PHP_EOL . 'Test Test; Test',
				'The new contents! ftest test 123 of the file' . PHP_EOL . 'Test. tttest 123' . PHP_EOL . 'Test Test; Test',
			]
		];
	}

	/**
	 * @test
	 * @dataProvider dpFilesWithTotallyDifferentContent
	 *
	 * @param $content1
	 * @param $content2
	 */
	public function itChecksFilesWithTotallyDifferentContent( $content1, $content2 ) {

		vfsStream::setup( 'home' );

		$file  = vfsStream::url( 'home/test.txt' );
		$file2 = vfsStream::url( 'home/test2.txt' );

		file_put_contents( $file, $content1 );
		file_put_contents( $file2, $content2 );

		$dd = new File($file, $file2);
		$this->assertSame( 0, $dd->getPercent() );
	}

	public function dpFilesWithTotallyDifferentContent() {
		return [
			[
				'Test Test Test',
				'fds fds fds'
			],
			[
				'qwe re   trrrty yt uuyuy uyt yt' . PHP_EOL . 'tr tttr tr' . PHP_EOL . 'tr tr rttr trt',
				'asdf as   dfds asdfa' . PHP_EOL . 'aa ss' . PHP_EOL . 'fdfdfd f fdf',
			],
			[
				'qqw rerer! trrrr re tt' . PHP_EOL . 'rere. tttrt' . PHP_EOL . 'oyoyp op; popt optr',
				'asdf fdas! adsf  fdsaf a' . PHP_EOL . 'fdsa fdafds. fdsaf fdas' . PHP_EOL . 'fdsa fdsfds; fdsafdsa',
			]
		];
	}

    /**
     * @test
     * @dataProvider dpFilesSameContent
     *
     * @param $content1
     * @param $content2
     */
    public function itChecksTextsSameContent( $content1, $content2 ) {
        $dd = new Text( $content1, $content2 );
        $this->assertEquals( 100, $dd->getPercent() );
    }

    /**
     * @test
     * @dataProvider dpFilesWithContentNotTotallyEqual
     *
     * @param $content1
     * @param $content2
     */
    public function itChecksTextsWithContentNotTotallyEqual( $content1, $content2 ) {
        $dd = new Text( $content1, $content2 );
        $this->assertTrue( $dd->getPercent() < 100 );
    }

    /**
     * @test
     * @dataProvider dpFilesWithTotallyDifferentContent
     *
     * @param $content1
     * @param $content2
     */
    public function itChecksTextsWithTotallyDifferentContent( $content1, $content2 ) {
        $dd = new Text($content1, $content2);
        $this->assertSame( 0, $dd->getPercent() );
    }
}