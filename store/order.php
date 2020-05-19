
<html>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-9aIt2nRpC12Uk9gS9baDl411NQApFmC26EwAOH8WgZl5MYYxFfc+NcPb1dKGj7Sk" crossorigin="anonymous">

<script src="https://code.jquery.com/jquery-3.5.1.min.js" integrity="sha256-9/aliU8dGd2tb6OSsuzixeV4y/faTqgFtohetphbbj0=" crossorigin="anonymous"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/js/bootstrap.min.js" integrity="sha384-OgVRvuATP1z7JjHLkuOU7Xw704+h835Lr+6QL9UvYjZE3Ipu6Tp75j7Bh/kR0JKI" crossorigin="anonymous"></script>

<script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.15.5/xlsx.full.min.js"></script>
<script src="table2excel/dist/jquery.table2excel.min.js" type="text/javascript"></script>

<script src="tableExport/tableExport.min.js" type="text/javascript"></script>
<script src="tableExport/libs/js-xlsx/xlsx.core.min.js" type="text/javascript"></script>
<script src="tableExport/libs/FileSaver/FileSaver.min.js" type="text/javascript"></script>
<script src="tableExport/libs/html2canvas/html2canvas.min.js" type="text/javascript"></script>
<script src="ee/dist/excellentexport.js" type="text/javascript"></script>
<style>
    .jb-x-small { font-size: x-small; }
</style>
<body>
<div>
    <input type="file" onchange="readExcel()" />
</div>
<form action="down.php" method="post" id="sendData">
<div>

    <table id="order-table" class="table">
        <thead class="thead-dark">
        <tr>
            <th  class="jb-x-small" class="jb-x-small" scope="col">주문번호</th>
            <th  class="jb-x-small" scope="col">주문상품명</th>
            <th  class="jb-x-small" scope="col"></th>
            <th  class="jb-x-small" scope="col">수량</th>
            <th  class="jb-x-small" scope="col">수령인</th>
            <th  class="jb-x-small" scope="col">우편번호</th>
            <th  class="jb-x-small" scope="col">주소</th>
            <th  class="jb-x-small" scope="col">전화번호</th>
            <th  class="jb-x-small" scope="col">핸드폰</th>
            <th  class="jb-x-small" scope="col">비고</th>
            <th  class="jb-x-small" scope="col"></th>
            <th  class="jb-x-small" scope="col"></th>
        </tr>
        </thead>
        <tbody id="body">


        </tbody>
    </table>

</div>
<div>
    <button action="/store/down.php" type="submit" class="d-inblock btn btn-sm btn-default-light bd-default download-excel-btn btn-primary" data-toggle="tooltip" data-placement="bottom" title="" data-original-title="주문서 다운로드">
        <i class="fa fa-download"></i><span class="mBtn"><em class="text-normal">주문서 다운로드</em></span>
    </button>
</form>
    <button id="initData" class="btn-danger" type="button">초기화</button>
</div>
</body>
<script>
    $.vsprintf = function jQuery_vsprintf( format, args ) {
        if( format == null ) {
            throw "Not enough arguments for vsprintf";
        }
        if( args == null ) {
            args = [];
        }

        function _sprintf_format( type, value, flags ) {

            // Similar to how perl printf works
            if( value == undefined ) {
                if( type == 's' ) {
                    return '';
                } else {
                    return '0';
                }
            }

            var result;
            var prefix = '';
            var fill = '';
            var fillchar = ' ';
            if( flags['short'] || flags['long'] || flags['long_long'] ) {
                /* This is pretty ugly, but as JS ignores bit lengths except
                 * somewhat when working with bit operators.
                 * So we fake a bit :) */
                switch( type ) {
                    case 'e':
                    case 'f':
                    case 'G':
                    case 'E':
                    case 'G':
                    case 'd': /* signed */
                        if( flags['short'] ) {
                            if( value >= 32767 ) {
                                value = 32767;
                            } else if( value <= -32767-1 ) {
                                value = -32767-1;
                            }
                        } else if ( flags['long'] ) {
                            if( value >= 2147483647 ) {
                                value = 2147483647;
                            } else if( value <= -2147483647-1 ) {
                                value = -2147483647-1;
                            }
                        } else /*if ( flags['long_long'] )*/ {
                            if( value >= 9223372036854775807 ) {
                                value = 9223372036854775807;
                            } else if( value <= -9223372036854775807-1 ) {
                                value = -9223372036854775807-1;
                            }
                        }
                        break;
                    case 'X':
                    case 'B':
                    case 'u':
                    case 'o':
                    case 'x':
                    case 'b': /* unsigned */
                        if( value < 0 ) {
                            /* Pretty ugly, but one only solution */
                            value = Math.abs( value ) - 1;
                        }
                        if( flags['short'] ) {
                            if( value >= 65535 ) {
                                value = 65535;
                            }
                        } else if ( flags['long'] ) {
                            if( value >= 4294967295 ) {
                                value = 4294967295;
                            }

                        } else /*if ( flags['long_long'] )*/ {
                            if( value >= 18446744073709551615 ) {
                                value = 18446744073709551615;
                            }

                        }
                        break;
                }
            }
            switch( type ) {
                case 'c':
                    result = String.fromCharCode( parseInt( value ) );
                    break;
                case 's':
                    result = value.toString();
                    break;
                case 'd':
                    result = (new Number( parseInt( value ) ) ).toString();
                    break;
                case 'u':
                    result = (new Number( parseInt( value ) ) ).toString();
                    break;
                case 'o':
                    result = (new Number( parseInt( value ) ) ).toString(8);
                    break;
                case 'x':
                    result = (new Number( parseInt( value ) ) ).toString(16);
                    break;
                case 'B':
                case 'b':
                    result = (new Number( parseInt( value ) ) ).toString(2);
                    break;
                case 'e':
                    var digits = flags['precision'] ? flags['precision'] : 6;
                    result = (new Number( value ) ).toExponential( digits ).toString();
                    break;
                case 'f':
                    var digits = flags['precision'] ? flags['precision'] : 6;
                    result = (new Number( value ) ).toFixed( digits ).toString();
                    break;
                case 'g':
                    var digits = flags['precision'] ? flags['precision'] : 6;
                    result = (new Number( value ) ).toPrecision( digits ).toString();
                    break;
                case 'X':
                    result = (new Number( parseInt( value ) ) ).toString(16).toUpperCase();
                    break;
                case 'E':
                    var digits = flags['precision'] ? flags['precision'] : 6;
                    result = (new Number( value ) ).toExponential( digits ).toString().toUpperCase();
                    break;
                case 'G':
                    var digits = flags['precision'] ? flags['precision'] : 6;
                    result = (new Number( value ) ).toPrecision( digits ).toString().toUpperCase();
                    break;
            }

            if(flags['+'] && parseFloat( value ) > 0 && ['d','e','f','g','E','G'].indexOf(type) != -1 ) {
                prefix = '+';
            }

            if(flags[' '] && parseFloat( value ) > 0 && ['d','e','f','g','E','G'].indexOf(type) != -1 ) {
                prefix = ' ';
            }

            if( flags['#'] && parseInt( value ) != 0 ) {
                switch(type) {
                    case 'o':
                        prefix = '0';
                        break;
                    case 'x':
                    case 'X':
                        prefix = '0x';
                        break;
                    case 'b':
                        prefix = '0b';
                        break;
                    case 'B':
                        prefix = '0B';
                        break;
                }
            }

            if( flags['0'] && !flags['-'] ) {
                fillchar = '0';
            }

            if( flags['width'] && flags['width'] > ( result.length + prefix.length ) ) {
                var tofill = flags['width'] - result.length - prefix.length;
                for( var i = 0; i < tofill; ++i ) {
                    fill += fillchar;
                }
            }

            if( flags['-'] && !flags['0'] ) {
                result += fill;
            } else {
                result = fill + result;
            }

            return prefix + result;
        };

        var result = "";

        var index = 0;
        var current_index = 0;
        var flags = {
            'long': false,
            'short': false,
            'long_long': false
        };
        var in_operator = false;
        var relative = false;
        var precision = false;
        var fixed = false;
        var vector = false;
        var bitwidth = false;
        var vector_delimiter = '.';

        for( var i = 0; i < format.length; ++i ) {
            var current_char = format.charAt(i);
            if( in_operator ) {
                // backward compat
                switch( current_char ) {
                    case 'i':
                        current_char = 'd';
                        break;
                    case 'D':
                        flags['long'] = true;
                        current_char = 'd';
                        break;
                    case 'U':
                        flags['long'] = true;
                        current_char = 'u';
                        break;
                    case 'O':
                        flags['long'] = true;
                        current_char = 'o';
                        break;
                    case 'F':
                        current_char = 'f';
                        break;
                }
                switch( current_char ) {
                    case 'c':
                    case 's':
                    case 'd':
                    case 'u':
                    case 'o':
                    case 'x':
                    case 'e':
                    case 'f':
                    case 'g':
                    case 'X':
                    case 'E':
                    case 'G':
                    case 'b':
                    case 'B':
                        var value = args[current_index];
                        if( vector ) {
                            var fixed_value = value;
                            if( value instanceof Array ) {
                                // if the value is an array, assume to work on it directly
                                fixed_value = value;
                            } else if ( typeof(value) == 'string' || value instanceof String ) {
                                // normal behavour, assume string is a bitmap
                                fixed_value = value.split('').map( function( value ) { return value.charCodeAt(); } );
                            } else if ( ( typeof(value) == 'number' || value instanceof Number ) && flags['bitwidth'] ) {
                                // if we defined a width, assume we want to vectorize the bits directly
                                fixed_value = [];
                                do {
                                    fixed_value.unshift( value & ~(~0 << flags['bitwidth'] ) );
                                } while( value >>>= flags['bitwidth'] );
                            } else {
                                fixed_value = value.toString().split('').map( function( value ) { return value.charCodeAt(); } );

                            }
                            result += fixed_value.map( function( value ) {
                                return _sprintf_format( current_char, value, flags );
                            }).join( vector_delimiter );
                        } else {
                            result += _sprintf_format( current_char, value, flags );
                        }
                        if( !fixed ) {
                            ++index;
                        }
                        current_index = index;
                        flags = {};
                        relative = false;
                        in_operator = false;
                        precision = false;
                        fixed = false;
                        vector = false;
                        bitwidth = false;
                        vector_delimiter = '.';
                        break;
                    case 'v':
                        vector = true;
                        break;
                    case ' ':
                    case '0':
                    case '-':
                    case '+':
                    case '#':
                        flags[current_char] = true;
                        break;
                    case '*':
                        relative = true;
                        break;
                    case '.':
                        precision = true;
                        break;
                    case '@':
                        bitwidth = true;
                        break;
                    case 'l':
                        if( flags['long'] ) {
                            flags['long_long'] = true;
                            flags['long'] = false;
                        } else {
                            flags['long'] = true;
                            flags['long_long'] = false;
                        }
                        flags['short'] = false;
                        break;
                    case 'q':
                    case 'L':
                        flags['long_long'] = true;
                        flags['long'] = false;
                        flags['short'] = false;
                        break;
                    case 'h':
                        flags['short'] = true;
                        flags['long'] = false;
                        flags['long_long'] = false;
                        break;
                }
                if( /\d/.test( current_char ) ) {
                    var num = parseInt( format.substr( i ) );
                    var len = num.toString().length;
                    i += len - 1;
                    var next = format.charAt( i  + 1 );
                    if( next == '$' ) {
                        if( num < 0 || num > args.length ) {
                            throw "out of bound";
                        }
                        if( relative ) {
                            if( precision ) {
                                flags['precision'] = args[num - 1];
                                precision = false;
                            } else if( format.charAt( i + 2 ) == 'v' ) {
                                vector_delimiter = args[num - 1];
                            }else {
                                flags['width'] = args[num - 1];
                            }
                            relative = false;
                        } else {
                            fixed = true;
                            current_index = num - 1;
                        }
                        ++i;
                    } else if( precision ) {
                        flags['precision'] = num;
                        precision = false;
                    } else if( bitwidth ) {
                        flags['bitwidth'] = num;
                        bitwidth = false;
                    } else {
                        flags['width'] = num;
                    }
                } else if ( relative && !/\d/.test( format.charAt( i + 1 ) ) ) {
                    if( precision ) {
                        flags['precision'] = args[current_index];
                        precision = false;
                    } else if( format.charAt( i + 1 ) == 'v' ) {
                        vector_delimiter = args[current_index];
                    } else {
                        flags['width'] = args[current_index];
                    }
                    ++index;
                    if( !fixed ) {
                        current_index++;
                    }
                    relative = false;
                }
            } else {
                if( current_char == '%' ) {
                    // If the next character is an %, then we have an escaped %,
                    // we'll take this as an exception to the normal lookup, as
                    // we don't want/need to process this.
                    if( format.charAt(i+1) == '%' ) {
                        result += '%';
                        ++i;
                        continue;
                    }
                    in_operator = true;
                    continue;
                } else {
                    result += current_char;
                    continue;
                }
            }
        }
        return result;
    };
    $.sprintf = function jQuery_sprintf() {
        if( arguments.length == 0 ) {
            throw "Not enough arguments for sprintf";
        }

        var args = Array.prototype.slice.call(arguments);
        var format = args.shift();

        return $.vsprintf( format, args );
    };

    var TEMPLATE = '<tr>\n' +
        '            <td class="jb-x-small">%s <input type="hidden" name="orderNo[]" value="adsfsdf"></td>\n' +
        '            <td class="jb-x-small">%s</td>\n' +
        '            <td class="jb-x-small"></td>\n' +
        '            <td class="jb-x-small">%s</td>\n' +
        '            <td class="jb-x-small">%s</td>\n' +
        '            <td class="jb-x-small">%s</td>\n' +
        '            <td class="jb-x-small">%s</td>\n' +
        '            <td class="jb-x-small">%s</td>\n' +
        '            <td class="jb-x-small">%s</td>\n' +
        '            <td class="jb-x-small">%s</td>\n' +
        '            <td class="jb-x-small"></td>\n' +
        '            <td class="jb-x-small"></td>\n' +
        '        </tr>';

    var sendDataArr = [];

    function readExcel() {
        let input = event.target;
        let reader = new FileReader();
        var today = getFormatDate();
        reader.onload = function () {
            let data = reader.result;
            let workBook = XLSX.read(data, { type: 'binary' });
            workBook.SheetNames.forEach(function (sheetName) {
                let rows = XLSX.utils.sheet_to_json(workBook.Sheets[sheetName]);
                $(rows).each(function (i,item) {
                    if (i>0){
                        var orderNo = today+'-'+i;
                        var itemName = '';
                        var recipient = item.__EMPTY_7;
                        var addr = item.__EMPTY_39;
                        var count = item.__EMPTY_17;
                        var postCode = item.__EMPTY_41;
                        var phone1 = item.__EMPTY_37;
                        var phone2 = item.__EMPTY_38;
                        var msg = item.__EMPTY_42;
                        if (item.__EMPTY_14 == '단일상품'){
                            itemName = item.__EMPTY_34;
                        }else{
                            itemName = item.__EMPTY_34 + ' - '+item.__EMPTY_15;
                        }

                        // sendDataArr[i]['key']

                        var insertData = $.sprintf(TEMPLATE,orderNo,itemName,count,recipient,postCode,addr,phone1,phone2,msg);
                        $('#body').append(insertData);
                    }
                })
            })
        };

        reader.readAsBinaryString(input.files[0]);
    }

    function getFormatDate(){
        var date = new Date();
        var year = date.getFullYear();              //yyyy
        var month = (1 + date.getMonth());          //M
        month = month >= 10 ? month : '0' + month;  //month 두자리로 저장
        var day = date.getDate();                   //d
        day = day >= 10 ? day : '0' + day;          //day 두자리로 저장
        return  year + '' + month + '' + day;
    }
    $(document).ready(function() {
        $( "#initData" ).submit(function( event ) {
            console.log( $( this ).serializeArray() );
            event.preventDefault();
        });
    });

    // $('.download-excel-btn').on('click',function () {
    //     var today = getFormatDate();
    //
    //     $('#order-table').table2excel({
    //         exclude : "noExl",
    //         name: "Worksheet Name",
    //         filename: today+"-발주.xls", // do include extension
    //         fileext : 'xls',
    //         exclude_img: true,
    //         exclude_links: true,
    //         exclude_inputs: true
    //     })
    //     // ExcellentExport.excel(this,'order-table',today+'-발주');
    //
    //     $.ajax({
    //
    //         url: "/store/down.php", // 클라이언트가 요청을 보낼 서버의 URL 주소
    //         data: {name: "홍길동"},                // HTTP 요청과 함께 서버로 보낼 데이터
    //         type: "POST",                             // HTTP 요청 방식(GET, POST)
    //         dataType: "json"                         // 서버에서 보내줄 데이터의 타입
    //     }).done(function (json) {
    //         console.log(json);
    //     }).fail(function (xhr, status, errorThrown) {
    //
    //     }).always(function (xhr, status) {
    //
    //     });
    // });

</script>
</html>