(function(window, $) {

	'use strict';

	const ROOT = {};

	ROOT.name	= {
		url		: window.location.origin + '/',
		path	: window.location.pathname.replace(/^\/(.*?)/g, ''),
		hash	: window.location.hash,
		param	: window.location.search
	};

	/**
	 * Main - Controller
	 *=====================================================*/
	ROOT.Main = function() {

		const init = function() {
			root();
		};

		const root = function() {
			log(ROOT.name);
			// new ROOT.Canvas();
		};

		init();
	};

	/**
	 * Canvas
	 *=====================================================*/
	ROOT.Canvas = function() {

		let $wrap			= null;
		let stage			= null;
		let container	= null;

		const scale	= 1.5;
		const range	= 300;

		const mouse	= {
			x : 0,
			y : 0
		};

		const defaults = {
			color	: 'rgba(0,0,0,1)',
			size	: 30
		};

		const items = [];

		const item_props = [
			{
				text: '採用情報サイトで知る',
				link : 3,
				baseX: '25%',
				baseY: '20%'
			},
			{
				text	: 'なんとなく社名を検索',
				link	: 3,
				baseX	: '45%',
				baseY	: '18%'
			},
			{
				text	: '企業サイトを見る',
				link	: 4,
				baseX	: '40%',
				baseY	: '45%'
			},
			{
				text	: '採用ページを見る',
				link	: 6,
				baseX	: '65%',
				baseY	: '50%'
			},
			{
				text	: '採用ページを見て興味を失くす',
				link	: 5,
				baseX	: '80%',
				baseY	: '60%'
			},
			{
				text	: '仕事の面白さと出会う',
				link	: 0,
				baseX	: '60%',
				baseY	: '80%'
			}
		];

		/*-------------------------------------------
		 * Initialize
		 *-------------------------------------------*/
		const init = function() {

			stage	= new createjs.Stage('stage');
			$wrap	= $(stage.canvas).parent();

			if( !stage.canvas ) {
				throw new Error('Cannot create a canvas.');
			}

			set_up();

			createjs.Ticker.setFPS(1);
			createjs.Ticker.timingMode = createjs.Ticker.RAF;
			createjs.Ticker.on('tick', function() {
				render();
			});
		};

		const set_up = function() {

			set_events();

			container = new createjs.Container();

			for( let i = 0; i < item_props.length; i++ ) {
				items[i] = new createjs.Shape();
				container.addChild(items[i]);
			}
			stage.addChild(container);
		};

		/*-------------------------------------------
		 * Renders
		 *-------------------------------------------*/
		const draw = function(item, prop) {
			prop = $.extend({}, defaults, prop);

			const shape = item;

			prop.baseX = float(prop.baseX, stage.canvas.width);
			prop.baseY = float(prop.baseY, stage.canvas.height);

			shape.x = prop.baseX;
			shape.y = prop.baseY;

			let _scale = 1;
			let distance = Math.sqrt( Math.pow(mouse.x - shape.x, 2) + Math.pow(mouse.y - shape.y, 2) ) - prop.size;

			if( distance < 0 ) {
				distance = 0;
			}

			if( distance <= range ) {
				_scale = ( (range - distance) * 0.01 ) / range * 100 * (scale - 1) + 1;
			}

			shape.scaleX = shape.scaleY = _scale;

			shape.graphics.beginFill(prop.color)
										.drawCircle(0, 0, prop.size)
										.endFill();

		};

		const render = function() {
			for( let i = 0; i < item_props.length; i++ ) {
				draw(items[i], item_props[i]);
			}
			stage.update();
		};

		/*-------------------------------------------
		 * UTIL
		 *-------------------------------------------*/
		const float = function( num_str, t ) {
			if( num_str.match('%') !== null ) {
				return parseFloat(num_str.match(/-?[0-9]+\.?[0-9]*/g)) / 100 * t;
			}
			else {
				return parseFloat(num_str);
			}
		}

		/*-------------------------------------------
		 * SET ENV
		 *-------------------------------------------*/

		const set_events = function() {

			const $window = $(window);

			$window.on('resize', function() {
				stage.canvas.width	= $wrap.width();
				stage.canvas.height	= $wrap.height();
			}).trigger('resize');

			mouse.x = stage.canvas.width / 2;
			mouse.y = stage.canvas.height / 2;

			$wrap.on('mousemove', function(e) {
				mouse.x = e.offsetX;
				mouse.y = e.offsetY;
			});

		};

		init();
	};


	/**
	 * ROOT - Load
	 *=====================================================*/
	$(function () {
		new ROOT.Main();
	});

})(this, this.jQuery);


/**
 * コンソール文の短縮
 */
var console_methods = [
	'log',
	'info',
	'error',
	'warn',
	'count',

	/*スタックトレース*/
	'trace',

	/*速度測定*/
	'time',
	'timeEnd',

	/*オブジェクト構造*/
	'table',
	'dir',
	'dirxml'
];

for( var i in console_methods ){
	(function(m) {
		if( console[m] ) {
			window[m] = console[m].bind(console);
		}
		else {
			window[m] = log;
		}
	})(console_methods[i]);
}
