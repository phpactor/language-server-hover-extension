<?php

namespace Phpactor\Extension\LanguageServerHover\Tests\Unit\Handler;

use LanguageServerProtocol\Hover;
use LanguageServerProtocol\TextDocumentIdentifier;
use LanguageServerProtocol\TextDocumentItem;
use Phpactor\Extension\LanguageServerHover\Tests\Unit\HoverTestCase;
use Phpactor\Extension\LanguageServer\Helper\OffsetHelper;
use Phpactor\LanguageServer\Core\Rpc\ResponseMessage;
use Phpactor\TestUtils\ExtractOffset;

class HoverHandlerTest extends HoverTestCase
{
    const PATH = 'file:///hello';

    /**
     * @dataProvider provideHover
     */
    public function testHover(string $test, string $expected)
    {
        [ $text, $offset ] = ExtractOffset::fromSource($test);

        $tester = $this->createTester();
        $tester->initialize();
        $item = new TextDocumentItem(self::PATH, 'php', 1, $text);
        $tester->openDocument($item);
        $responses = $tester->dispatch('textDocument/hover', [
            'textDocument' => new TextDocumentIdentifier(self::PATH),
            'position' => OffsetHelper::offsetToPosition($text, $offset)
        ]);
        $tester->assertSuccess($responses);
        $response = $responses[0];
        $this->assertInstanceOf(ResponseMessage::class, $response);
        $result = $response->result;
        $this->assertInstanceOf(Hover::class, $result);
        $this->assertEquals($result->contents, $expected);
    }

    public function provideHover()
    {
        yield 'var' => [
            '<?php $foo = "foo"; $f<>oo;',
            'string'
        ];

        yield 'poperty' => [
            '<?php class A { private $<>b; }',
            'pri $b'
        ];

        yield 'method' => [
            '<?php class A { private function f<>oo():string {} }',
            'pri foo(): string'
        ];

        yield 'class' => [
            '<?php cl<>ass A { } }',
            'A'
        ];
    }
}
