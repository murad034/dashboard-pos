@extends('layouts.AdminLTE.index')

@section('icon_page', 'receipt')

@section('title', 'Customer Receipt Designer(CRD)')


@section('content')
<link href="{{ asset('/js/designer/customer/builder/css/receiptline-designer.css') }}" rel="stylesheet">
<script type="text/javascript" src="{{ asset('/js/designer/customer/builder/script/receiptline-designer.js') }}">
</script>
<script type="text/javascript" src="{{ asset('/js/designer/customer/builder/script/receiptline.js') }}"></script>
<script type="text/javascript" src="{{ asset('/js/designer/customer/builder/script/qrcode-generator/qrcode.js') }}">
</script>

<div class="row">
  <div class="col-xs-12">
    <div class="box">
      <div class="box-body" id="edit-design-panel">
        <header>
          <div>
            <button id="load" class="hidden" data-tooltip="open">
              <svg width="24px" height="24px" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg" version="1.1">
                <path stroke="#000" fill="none" stroke-width="1" d="M.5,23.5l4,-15h19l-4,15zv-19h2l1,-2h5l1,2h10v4" />
              </svg>
            </button>
            <button id="save" class="hidden" data-tooltip="download">
              <svg width="24px" height="24px" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg" version="1.1">
                <path stroke="#000" fill="none" stroke-width="1"
                  d="M11.5,.5v16m-4,-4l4,4l4,-4M4.5,8.5h-4v15h22v-15h-4" />
              </svg>
            </button>
            <button id="stnm" class="" data-tooltip="">Store Name</button>
            <button id="stabn" class="" data-tooltip="">Store ABN</button>
            <button id="stphone" class="" data-tooltip="">Store Phone</button>
            <button id="staddress" class="" data-tooltip="">Store Address</button>
            <button id="recnum" class="" data-tooltip="">Receipt Number</button>
            <button id="stfname" class="" data-tooltip="">Staff Name</button>
            <button id="timestmp" class="" data-tooltip="">Time Stamp</button>
            <button id="saleinfo" class="" data-tooltip="">Receipt Items</button>
            <input id="zoom" type="range" class="hidden" value="20" min="10" max="30" step="2">
          </div>
          <div>
            <button id="img" class="" data-tooltip="image">
              <svg width="24px" height="24px" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg" version="1.1">
                <path stroke="#000" fill="none" stroke-width="1" d="M.5,.5h23v23h-23zM.5,17.5l15,-10l8,8" />
                <circle stroke="#000" fill="none" stroke-width="1" cx="6" cy="6" r="2.5" />
              </svg>
            </button>
            <button id="bar" class="" data-tooltip="barcode">
              <svg width="24px" height="24px" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg" version="1.1">
                <path fill="#000"
                  d="M0,1h2v20h-2zM4,1h1v20h-1zM6,1h1v20h-1zM8,1h1v20h-1zM10,1h1v20h-1zM13,1h1v20h-1zM15,1h1v20h-1zM17,1h1v20h-1zM19,1h1v20h-1zM22,1h2v20h-2z" />
                <path fill="#000" d="M3,22h2v2h-2zM7,22h2v2h-2zM11,22h2v2h-2zM15,22h2v2h-2zM19,22h2v2h-2z" />
              </svg>
            </button>
            <button id="qr" class="" data-tooltip="2D code">
              <svg width="24px" height="24px" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg" version="1.1">
                <path fill="#000" d="M0,0h5v5h-5zM19,0h5v5h-5zM0,19h5v5h-5z" />
                <path fill="#000" d="M7,1h2v2h-2zM11,1h2v2h-2zM15,1h2v2h-2z" />
                <path fill="#000" d="M9,3h2v2h-2zM13,3h2v2h-2z" />
                <path fill="#000" d="M7,5h2v2h-2zM11,5h2v2h-2zM15,5h2v2h-2z" />
                <path fill="#000" d="M1,7h2v2h-2zM5,7h2v2h-2zM9,7h2v2h-2zM13,7h2v2h-2zM17,7h2v2h-2zM21,7h2v2h-2z" />
                <path fill="#000" d="M3,9h2v2h-2zM7,9h2v2h-2zM11,9h2v2h-2zM15,9h2v2h-2zM19,9h2v2h-2z" />
                <path fill="#000"
                  d="M1,11h2v2h-2zM5,11h2v2h-2zM9,11h2v2h-2zM13,11h2v2h-2zM17,11h2v2h-2zM21,11h2v2h-2z" />
                <path fill="#000" d="M3,13h2v2h-2zM7,13h2v2h-2zM11,13h2v2h-2zM15,13h2v2h-2zM19,13h2v2h-2z" />
                <path fill="#000"
                  d="M1,15h2v2h-2zM5,15h2v2h-2zM9,15h2v2h-2zM13,15h2v2h-2zM17,15h2v2h-2zM21,15h2v2h-2z" />
                <path fill="#000" d="M7,17h2v2h-2zM11,17h2v2h-2zM15,17h2v2h-2zM19,17h2v2h-2z" />
                <path fill="#000" d="M9,19h2v2h-2zM13,19h2v2h-2zM17,19h2v2h-2zM21,19h2v2h-2z" />
                <path fill="#000" d="M7,21h2v2h-2zM11,21h2v2h-2zM15,21h2v2h-2zM19,21h2v2h-2z" />
              </svg>
            </button>
            <button id="format" class="" data-tooltip="formatting">
              <svg width="24px" height="24px" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg" version="1.1">
                <path stroke="#000" fill="none" stroke-width="1"
                  d="M.5,.5v23M.5,11.5h23M23.5,.5v23M4.5,7.5l-4,4l4,4M19.5,7.5l4,4l-4,4" />
              </svg>
            </button>
            <button id="col" class="" data-tooltip="column delimiter">
              <svg width="24px" height="24px" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg" version="1.1">
                <path stroke="#000" fill="none" stroke-width="1" d="M11.5,.5v23" />
              </svg>
            </button>
            <button id="hr" class="" data-tooltip="horizontal rule">
              <svg width="24px" height="24px" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg" version="1.1">
                <path stroke="#000" fill="none" stroke-width="1" d="M.5,11.5h23" />
              </svg>
            </button>
            <button id="cut" class="" data-tooltip="paper cut">
              <svg width="24px" height="24px" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg" version="1.1">
                <path stroke="#000" fill="none" stroke-width="1"
                  d="M12,12.5l-7.5,-3a2,2,0,1,1,.5,0M12,11.5l-7.5,3a2,2,0,1,0,.5,0" />
                <path fill="#000" d="M12,12l10,-4q-1,-1,-2.5,-1l-10,4v2l10,4q1.5,0,2.5,-1z" />
              </svg>
            </button>
            <button id="ul" class="" data-tooltip="underline">
              <svg width="24px" height="24px" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg" version="1.1">
                <text x="12" y="20" text-anchor="middle" fill="#000" font-family="monospace" font-size="24"
                  text-decoration="underline">a
                </text>
              </svg>
            </button>
            <button id="em" class="" data-tooltip="emphasis">
              <svg width="24px" height="24px" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg" version="1.1">
                <text x="12" y="20" text-anchor="middle" fill="#000" font-family="monospace" font-size="24"
                  font-weight="bold">a
                </text>
              </svg>
            </button>
            <button id="iv" class="" data-tooltip="invert">
              <svg width="24px" height="24px" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg" version="1.1">
                <path fill="#000" d="M0,0h24v24h-24z" />
                <text x="12" y="20" text-anchor="middle" fill="#fff" font-family="monospace" font-size="24">a
                </text>
              </svg>
            </button>
            <button id="wh" class="" data-tooltip="size">
              <svg width="24px" height="24px" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg" version="1.1">
                <text x="6" y="20" text-anchor="middle" fill="#000" font-family="monospace" font-size="24"
                  transform="scale(2,1)">a
                </text>
              </svg>
            </button>
          </div>
          <div class="hidden">
            <label for="linewidth">Width</label>
            <input id="linewidth" type="range" value="576" min="288" max="576" step="12">
            <span id="dots">576</span> dots / <span id="cpl">48</span> cpl
            <input id="linespace" type="checkbox">
            <label for="linespace">Spacing</label>
          </div>
          <div class="hidden">
            <input id="printerid" type="text" size="8" value="printer1">
            <button id="send">Send</button>
          </div>
        </header>
        <br>
        <main id="main">
          <textarea id="edit" rows="15" cols="80" placeholder="ReceiptLine Text" autofocus="autofocus"></textarea>
          <section>
            <div id="paper" class="receipt"></div>
          </section>
        </main>
        <br>
        <details>
          <summary>Grammar</summary>
          <article>
            <div>
              <strong>Columns</strong>
              <ul>
                <li>1st</li>
                <li><b>|</b>1st<b>|</b></li>
                <li>1st<b>|</b>2nd</li>
                <li><b>|</b>1st<b>|</b>2nd<b>|</b></li>
                <li>1st<b>|</b> ... <b>|</b>nth</li>
                <li><b>|</b>1st<b>|</b> ... <b>|</b>nth<b>|</b></li>
              </ul>
              <strong>Alignment</strong>
              <ul>
                <li>center</li>
                <li><b>|</b>center<b>|</b></li>
                <li><b>|␣</b>center<b>␣|</b></li>
                <li><b>|</b>left</li>
                <li><b>|</b>left<b>␣|</b></li>
                <li>left<b>␣|</b></li>
                <li>right<b>|</b></li>
                <li><b>|</b>␣right<b>|</b></li>
                <li><b>|</b>␣right</li>
              </ul>
            </div>
            <div>
              <strong>Special characters in text</strong>
              <ul>
                <li><b>\</b> : character escape</li>
                <li><b>|</b> : column delimiter</li>
                <li><b>{</b> : property delimiter (start)</li>
                <li><b>}</b> : property delimiter (end)</li>
                <li><b>-</b> (1 or more, exclusive) : horizontal rule</li>
                <li><b>=</b> (1 or more, exclusive) : paper cut</li>
                <li><b>~</b> : space</li>
                <li><b>_</b> : underline</li>
                <li><b>"</b> : emphasis</li>
                <li><b>`</b> : invert</li>
                <li><b>^</b> : double width</li>
                <li><b>^^</b> : double height</li>
                <li><b>^^^</b> : 2x size</li>
                <li><b>^^^^</b> : 3x size</li>
                <li><b>^^^^^</b> : 4x size</li>
                <li><b>^^^^^^</b> : 5x size</li>
                <li><b>^^^^^^^</b> (7 or more) : 6x size</li>
              </ul>
            </div>
            <div>
              <strong>Escape characters in text</strong>
              <ul>
                <li><b>\\</b> : \</li>
                <li><b>\|</b> : |</li>
                <li><b>\{</b> : {</li>
                <li><b>\}</b> : }</li>
                <li><b>\-</b> : - (cancel horizontal rule)</li>
                <li><b>\=</b> : = (cancel paper cut)</li>
                <li><b>\~</b> : ~</li>
                <li><b>\_</b> : _</li>
                <li><b>\"</b> : "</li>
                <li><b>\`</b> : `</li>
                <li><b>\^</b> : ^</li>
                <li><b>\n</b> : wrap text manually</li>
                <li><b>\x<i>nn</i></b> : hexadecimal character code</li>
                <li><b>\<i>char</i></b> (others) : ignore</li>
              </ul>
            </div>
            <div>
              <strong>Properties</strong>
              <ul>
                <li><b>{i</b>|<b>image:</b> base64 png format<b>}</b></li>
                <li class="nolist">image (recommended: monochrome, critical chunks only)</li>
                <li><b>{c</b>|<b>code:</b> string<b>}</b></li>
                <li class="nolist">barcode / 2D code</li>
                <li><b>{o</b>|<b>option:</b> value, value ...<b>}</b></li>
                <li class="nolist">barcode / 2D code options (default: code128 2 72 nohri / 3 L)
                </li>
                <li><b>{a</b>|<b>align:</b> left|center|right<b>}</b></li>
                <li class="nolist">line alignment (default: center)</li>
                <li><b>{w</b>|<b>width:</b> number|*, number|* ...<b>}</b></li>
                <li class="nolist">column width (default: auto)</li>
                <li><b>{b</b>|<b>border:</b> none|space|line|0-2<b>}</b></li>
                <li class="nolist">column border (default: space)</li>
                <li><b>{t</b>|<b>text:</b> wrap|nowrap<b>}</b></li>
                <li class="nolist">text wrapping (default: wrap)</li>
                <li><b>{x</b>|<b>command:</b> string<b>}</b></li>
                <li class="nolist">device-specific commands</li>
                <li><b>{_</b>|<b>comment:</b> string<b>}</b></li>
                <li class="nolist">comment</li>
              </ul>
            </div>
            <div>
              <strong>Special characters in property values</strong>
              <ul>
                <li><b>\</b> : character escape</li>
                <li><b>|</b> : column delimiter</li>
                <li><b>{</b> : property delimiter (start)</li>
                <li><b>}</b> : property delimiter (end)</li>
                <li><b>:</b> : key-value separator</li>
                <li><b>;</b> : key-value delimiter</li>
              </ul>
              <strong>Escape characters in property</strong>
              <ul>
                <li><b>\\</b> : \</li>
                <li><b>\|</b> : |</li>
                <li><b>\{</b> : {</li>
                <li><b>\}</b> : }</li>
                <li><b>\;</b> : ;</li>
                <li><b>\n</b> : new line</li>
                <li><b>\x<i>nn</i></b> : hexadecimal character code</li>
                <li><b>\<i>char</i></b> (others) : ignore</li>
              </ul>
            </div>
          </article>
        </details>
        <details>
          <summary>Syntax</summary>
          <article>
            <div>
              <figure>
                <figcaption>document</figcaption>
                <img alt="document" src="{{ asset('/js/designer/customer/builder/image/document.png') }}">
              </figure>
              <figure>
                <figcaption>line</figcaption>
                <img alt="line" src="{{ asset('/js/designer/customer/builder/image/line.png') }}">
              </figure>
              <figure>
                <figcaption>columns</figcaption>
                <img alt="columns" src="{{ asset('/js/designer/customer/builder/image/columns.png') }}">
              </figure>
              <figure>
                <figcaption>column</figcaption>
                <img alt="column" src="{{ asset('/js/designer/customer/builder/image/column.png') }}">
              </figure>
            </div>
            <div>
              <figure>
                <figcaption>text</figcaption>
                <img alt="text" src="{{ asset('/js/designer/customer/builder/image/text.png') }}">
              </figure>
              <figure>
                <figcaption>char</figcaption>
                <img alt="char" src="{{ asset('/js/designer/customer/builder/image/char.png') }}">
              </figure>
              <figure>
                <figcaption>escape</figcaption>
                <img alt="escape" src="{{ asset('/js/designer/customer/builder/image/escape.png') }}">
              </figure>
              <figure>
                <figcaption>ws (whitespace)</figcaption>
                <img alt="ws" src="{{ asset('/js/designer/customer/builder/image/ws.png') }}">
              </figure>
            </div>
            <div>
              <figure>
                <figcaption>property</figcaption>
                <img alt="property" src="{{ asset('/js/designer/customer/builder/image/property.png') }}">
              </figure>
              <figure>
                <figcaption>member</figcaption>
                <img alt="member" src="{{ asset('/js/designer/customer/builder/image/member.png') }}">
              </figure>
              <figure>
                <figcaption>key</figcaption>
                <img alt="key" src="{{ asset('/js/designer/customer/builder/image/key.png') }}">
              </figure>
              <figure>
                <figcaption>value</figcaption>
                <img alt="value" src="{{ asset('/js/designer/customer/builder/image/value.png') }}">
              </figure>
            </div>
          </article>
        </details>
        <div id="loaddialog" class="dialog">
          <div id="loadcancel"></div>
          <div id="loadbox">
            <input id="loadfile" type="file" accept="text/plain">
            <br>
            <textarea id="loadview" rows="12" cols="40" readonly></textarea>
            <hr>
            <div>
              <button id="loadok">OK</button>
            </div>
          </div>
        </div>
        <div id="savedialog" class="dialog">
          <div id="savecancel"></div>
          <div id="savebox">
            <div>Download</div>
            <input type="radio" id="savetext" name="savetype" value="text" checked>
            <label for="savetext">ReceiptLine Text</label>
            <br>
            <input type="radio" id="savesvg" name="savetype" value="svg">
            <label for="savesvg">SVG Image</label>
            <hr>
            <div>
              <button id="saveok">OK</button>
            </div>
          </div>
        </div>
        <div id="imgdialog" class="dialog">
          <div id="imgcancel"></div>
          <div id="imgbox">
            <input id="imgfile" type="file" accept="image/png">
            <br>
            <canvas id="imgview"></canvas>
            <hr>
            <div>
              <button id="imgok">OK</button>
            </div>
          </div>
        </div>
        <div id="bardialog" class="dialog">
          <div id="barcancel"></div>
          <div id="barbox">
            <label for="bardata">Data</label>
            <input id="bardata" type="text" value="1234">
            <br>
            <label for="bartype">Type</label>
            <select id="bartype">
              <option value="code128">CODE128</option>
              <option value="code93">CODE93</option>
              <option value="nw7">NW-7</option>
              <option value="codabar">Codabar</option>
              <option value="itf">ITF</option>
              <option value="code39">CODE39</option>
              <option value="jan">JAN</option>
              <option value="ean">EAN</option>
              <option value="upc">UPC</option>
            </select>
            <br>
            <label for="barwidth">Width</label>
            <input id="barwidth" type="range" value="2" min="2" max="4" step="1">
            <br>
            <label for="barheight">Height</label>
            <input id="barheight" type="range" value="72" min="24" max="240" step="24">
            <br>
            <input id="barhri" type="checkbox">
            <label for="barhri">HRI</label>
            <div class="note">Quiet zones are required around the barcode.</div>
            <div class="note">HRI: Human Readable Interpretation</div>
            <hr>
            <div>
              <button id="barok">OK</button>
            </div>
          </div>
        </div>
        <div>
          <div id="qrdialog" class="dialog">
            <div id="qrcancel"></div>
            <div id="qrbox">
              <label for="qrdata">Data</label>
              <input id="qrdata" type="text" value="index.html">
              <br>
              <label for="qrtype">Type</label>
              <select id="qrtype">
                <option value="qrcode">QR Code</option>
              </select>
              <br>
              <label for="qrcell">Size</label>
              <input id="qrcell" type="range" value="3" min="3" max="8" step="1">
              <br>
              <label for="qrlevel">Error correction level</label>
              <select id="qrlevel">
                <option value="L">L</option>
                <option value="M">M</option>
                <option value="Q">Q</option>
                <option value="H">H</option>
              </select>
              <div class="note">Quiet zones are required around the 2D code.</div>
              <div class="note">QR Code is a registered trademark of DENSO WAVE INCORPORATED.</div>
              <hr>
              <div>
                <button id="qrok">OK</button>
              </div>
            </div>
          </div>
        </div>
        <div>
          <div id="formatdialog" class="dialog">
            <div id="formatcancel"></div>
            <div id="formatbox">
              <div class="note">
                <svg width="325px" height="100px" viewBox="0 0 325 100" xmlns="http://www.w3.org/2000/svg"
                  version="1.1">
                  <path stroke="none" fill="#fff" stroke-width="1" d="M0,0h325v100h-325z" />
                  <path stroke="none" fill="#ddd" stroke-width="1" d="M50,25h225v50h-225z" />
                  <path stroke="none" fill="#bbb" stroke-width="1"
                    d="M50,25h25v50h-25zM150,25h25v50h-25zM250,25h25v50h-25z" />
                  <path stroke="#000" fill="none" stroke-width="1" d="M62.5,25.5v49M162.5,25.5v49M262.5,25.5v49" />
                  <path stroke="#000" fill="none" stroke-width="1"
                    d="M.5,.5v99M324.5,.5v99M.5,49.5h49m-4,-4l4,4l-4,4M324.5,49.5h-49m4,-4l-4,4l4,4" />
                  <path stroke="#000" fill="none" stroke-width="1"
                    d="M75.5,49.5m4,-4l-4,4l4,4m-4,-4h74m-4,-4l4,4l-4,4M175.5,49.5m4,-4l-4,4l4,4m-4,-4h74m-4,-4l4,4l-4,4" />
                  <text x="25" y="45" text-anchor="middle" fill="#000" font-size="10">align</text>
                  <text x="62.5" y="20" text-anchor="middle" fill="#000" font-size="10">border
                  </text>
                  <text x="112.5" y="45" text-anchor="middle" fill="#000" font-size="10">width
                  </text>
                  <text x="162.5" y="20" text-anchor="middle" fill="#000" font-size="10">border
                  </text>
                  <text x="212.5" y="45" text-anchor="middle" fill="#000" font-size="10">width
                  </text>
                  <text x="262.5" y="20" text-anchor="middle" fill="#000" font-size="10">border
                  </text>
                  <text x="300" y="45" text-anchor="middle" fill="#000" font-size="10">align
                  </text>
                  <text x="112.5" y="70" text-anchor="middle" fill="#000" font-size="10">text
                  </text>
                  <text x="110" y="85" text-anchor="end" fill="#000" font-size="10">wrap</text>
                  <path stroke="#000" fill="none" stroke-width="1"
                    d="M112.5,82.5h33q3 0,3 3q0 3,-3 3h-30q-3 0,-3 3q0 3,3 3h33m-4,-4l4,4l-4,4" />
                  <text x="212.5" y="70" text-anchor="middle" fill="#000" font-size="10">text
                  </text>
                  <text x="220" y="85" text-anchor="end" fill="#000" font-size="10">nowrap</text>
                  <path stroke="#000" fill="none" stroke-width="1" d="M222.5,82.5h26m-4,-4l4,4l-4,4M249.5,78.5v9" />
                </svg>
              </div>
              <label for="formatwidth">Column width</label>
              <input id="formatwidth" type="text" value="auto">
              <div class="note">default: auto (* for all columns), example: *,5,10</div>
              <label for="formatborder">Column border</label>
              <select id="formatborder">
                <option value="">-</option>
                <option value="none">none</option>
                <option value="space">space</option>
                <option value="line">line</option>
                <option value="0">0 (none)</option>
                <option value="1">1 (space)</option>
                <option value="2">2</option>
              </select>
              <div class="note">default: space</div>
              <label for="formattext">Text wrapping</label>
              <select id="formattext">
                <option value="">-</option>
                <option value="wrap">wrap</option>
                <option value="nowrap">nowrap</option>
              </select>
              <div class="note">default: wrap</div>
              <label for="formatalign">Line alignment</label>
              <select id="formatalign">
                <option value="">-</option>
                <option value="left">left</option>
                <option value="center">center</option>
                <option value="right">right</option>
              </select>
              <div class="note">default: center</div>
              <div>
                <button id="formatok">OK</button>
              </div>
            </div>
          </div>
        </div>
        <br>
        <br>
        <button type="button" id="saveTemplate" onclick="saveReceipt()" class="profile-btn savebut"
          style="border: none;">Save
          Template
        </button>
        <button type="button" id="saveReceipt" onclick="saveReceiptDraft()" class="profile-btn savebut"
          style="border: none;">
          Save Draft
        </button>
        <button type="button" id="saveReceiptWithSchedulePopUp" class="profile-btn savebut set-schedule"
          style="border: none;" data-bs-toggle="true" data-bs-html="true" data-bs-saitize="false">
          Save & Schedule
        </button>
        <section>
          <div id="PopoverContent" class="d-none">
            <div class="row" style="padding-top: 5px;">
              <div class="col-sm-9 text-center">
                <input type="datetime-local" id="set_schedule_at" class="form-control" name="schedule_at">
              </div>
              <div class="col-sm-3 text-center">
                <button type="button" class="btn btn-block btn-dark savbut" style="margin-left:-9px; margin-top:0px;"
                  onclick="saveReceiptDraftWithScheduleAt()">Save
                </button>
              </div>
            </div>
          </div>
        </section>

      </div>
      <!-- /.box-body -->
    </div>
    <!-- /.box -->
  </div>
  <!-- /.col -->
</div>
<script>
$.ajaxSetup({
  headers: {
    'X-CSRF-Token': $('meta[name="csrf-token"]').attr('content')
  }
});
let template_id = '{{$template_id}}';
</script>
<script src="{{ asset('/js/designer/customer/builder.js') }}"></script>
@endsection
