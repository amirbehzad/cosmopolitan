<?php

namespace Salarmehr\Cosmopolitan;

use PHPUnit\Framework\TestCase;

class IntlTest extends TestCase
{
    public function languageProvider()
    {
        return [
            ['en', 'en', 'English'],
            ['en', 'en_AU', 'English'],
            ['fa', 'en', 'انگلیسی'],
            ['fa', 'fa', 'فارسی'],
        ];
    }

    /**
     * @dataProvider languageProvider
     */
    public function testLanguage($local, $language, $name)
    {
        $intl = new Intl($local);
        $this->assertEquals($intl->language($language), $name);
    }

    public function testPercentage()
    {
        $actual = Intl::create('en_AU')->percentage(.2);
        $this->assertEquals('20%', $actual);
    }

    public function quoteProvider()
    {
        return [
            ['en', 'text', '“text”'],
            ['fa', 'text', '«text»'],
            ['ar', 'text', '”text“'],
            ['sp', 'text', '“text”'],
            ['ch', 'text', '“text”'],
            ['chi', 'text', '“text”'],
        ];
    }

    /**
     * @dataProvider quoteProvider
     */
    public function testQuote($local, $text, $quote)
    {
        $intl = new Intl($local);
        $this->assertEquals($intl->quote($text), $quote);
    }

    public function testGet()
    {
        $actual = Intl::create('en_AU')->get(Bundle::LOCALE, 'listPattern')->get('standard')->get('end');
        $this->assertEquals('{0} and {1}', $actual);
    }

    public function messageProvider()
    {
        return [
            ['en', 'aa {b} {c} dd', ['b' => 'bb', 'c' => 'cc'], 'aa bb cc dd'],
            [
                "en_US",
                "{0,number,integer} monkeys on {1,number,integer} trees make {2,number} monkeys per tree",
                [4560, 123, 4560 / 123],
                '4,560 monkeys on 123 trees make 37.073 monkeys per tree',
            ],
            [
                "de",
                "{0,number,integer} Affen auf {1,number,integer} Bäumen sind {2,number} Affen pro Baum",
                [4560, 123, 4560 / 123],
                '4.560 Affen auf 123 Bäumen sind 37,073 Affen pro Baum',
            ],
        ];
    }

    /**
     * @dataProvider messageProvider
     */
    public function testMessage($local, $text, $arguments, $message)
    {
        $intl = new Intl($local);
        $this->assertEquals($intl->message($text, $arguments), $message);
    }

    public function testCurrency()
    {
        $actual = Intl::create('en_AU')->currency('aud');
        $this->assertEquals('Australian Dollar', $actual);

        $actual = Intl::create('en_AU')->currency('aud', true);
        $this->assertEquals('$', $actual);

        $this->expectException(Exception::class);
        Intl::create('en_AU')->currency('foo', false, true);
    }

    public function testMoney()
    {
        $actual = Intl::create('en_AU')->money(12.3, 'aud');
        $this->assertEquals('$12.30', $actual);

        $actual = Intl::create('en_US')->money(12.3, 'aud');
        $this->assertEquals('A$12.30', $actual);
    }

    public function testOrdinal()
    {
        $actual = Intl::create('en_AU')->ordinal(1);
        $this->assertEquals('1st', $actual);
    }

    public function countryProvider()
    {
        return [
            ['en', 'AU', 'Australia'],
            ['en', 'en_AU', 'Australia'],
            ['fa', 'AU', 'استرالیا'],
        ];
    }

    /**
     * @dataProvider countryProvider
     */
    public function testCountry($local, $country, $name)
    {
        $intl = new Intl($local);
        $this->assertEquals($intl->country($country), $name);
    }

    public function testDuration()
    {
        $actual = Intl::create('en_US')->duration(1222060);
        $this->assertEquals('339:27:40', $actual);

        $actual = Intl::create('en_US')->duration(1222060, true);
        $this->assertEquals('339 hours, 27 minutes, 40 seconds', $actual);
    }
}