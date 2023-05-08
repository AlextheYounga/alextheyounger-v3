"use strict";

var terrainLoaded = false; // global terrain variable
function _createForOfIteratorHelperLoose(o, allowArrayLike) {
	var it; if (typeof Symbol === "undefined" || o[Symbol.iterator] == null) {
		if (Array.isArray(o) || (it = _unsupportedIterableToArray(o)) || allowArrayLike && o && typeof o.length === "number") {
			if (it) o = it; var i = 0; return function () {
				if (i >= o.length) return { done: true };
				return { done: false, value: o[i++] };
			};
		}
		throw new TypeError("Invalid attempt to iterate non-iterable instance.\nIn order to be iterable, non-array objects must have a [Symbol.iterator]() method.");
	}
	it = o[Symbol.iterator]();
	return it.next.bind(it);
}

function _unsupportedIterableToArray(o, minLen) {
	if (!o) return; if (typeof o === "string") return _arrayLikeToArray(o, minLen);
	var n = Object.prototype.toString.call(o).slice(8, -1);
	if (n === "Object" && o.constructor) n = o.constructor.name;
	if (n === "Map" || n === "Set") return Array.from(o);
	if (n === "Arguments" || /^(?:Ui|I)nt(?:8|16|32)(?:Clamped)?Array$/.test(n)) return _arrayLikeToArray(o, minLen);
}

function _arrayLikeToArray(arr, len) {
	if (len == null || len > arr.length) len = arr.length;
	for (var i = 0, arr2 = new Array(len); i < len; i++) {
		arr2[i] = arr[i];
	}
	return arr2;
}

var SVG = function SVG(opt) {
	var target = null;
	var offset = 0;
	var px = -1;
	var py = -1;
	var iter = 0;
	var cpuTime = 7;
	var polyline = [];

	var init = function init() { };

	var polylines = [];
	var matrixTransform = false; //calling setup() and draw() from terrain.js

	var autorun = function autorun() {
		if (typeof setup === "function") init = setup;
		init();
		if (typeof draw === "function") render();
	}; ///////////////////////////////////////////////////////////////////////

	var initSVG = function initSVG(opt) {
		if (opt.centerOrigin !== undefined && opt.centerOrigin === true) offset = 95; //Not sure this does anything.				

		if (opt.cpuTime !== undefined) cpuTime = opt.cpuTime;
		var svgElem = document.querySelector("#terrain");
		svgElem.setAttribute("viewBox", opt.viewBox || "0 0 200 200");
		svgElem.setAttribute("style", "background:" + (opt.background || "#fff"));
		svgElem.setAttribute("viewport-fill", opt.background || "#fff");
		var inside = document.createElementNS("http://www.w3.org/2000/svg", "g");
		inside.setAttribute("stroke-linejoin", "round");
		inside.setAttribute("style", "fill:none;stroke:" + (opt.stroke || "#000") + ";stroke-width:" + (opt.strokeWidth || 0.2) + ";opacity:" + (opt.opacity || 1));
		svgElem.appendChild(inside);
		target = inside;
		// var outside = document.createElementNS("http://www.w3.org/2000/svg", "g");
		// outside.setAttribute("style", "fill:none;stroke:" + (opt.stroke || "#000") + ";stroke-width:0.3;");
		// svgElem.appendChild(outside);
		requestAnimationFrame(autorun);
		// return [svgElem, inside, outside];
		return [svgElem, inside];
	};

	var _initSVG = initSVG(opt),
		svgElem = _initSVG[0],
		inside = _initSVG[1],
		outside = _initSVG[2]; ///////////////////////////////////////////////////////////////////////////


	var start = function start() {
		matrixTransform = false;
		matrix.data = [1, 0, 0, 1, 0, 0];
		matrix.stack.length = 0;
		polyline = [];
		polylines.length = 0;
		inside.innerHTML = "";
		// outside.innerHTML = "";
		init();
		if (typeof draw === "function") render();
	};

	var moveTo = function moveTo(x0, y0) {
		var x, y;

		if (Array.isArray(x0)) {
			y = x0[1];
			x = x0[0];
		} else {
			x = x0;
			y = y0;
		}

		if (matrixTransform === true) {
			matrix.transform(x, y);
		} else {
			px = x;
			py = y;
		}

		if (polyline.length > 0) polylineSVG(true);
		polyline.push(px, py);
	};

	var lineTo = function lineTo(x0, y0) {
		var x, y;

		if (Array.isArray(x0)) {
			y = x0[1];
			x = x0[0];
		} else {
			x = x0;
			y = y0;
		}

		if (matrixTransform === true) {
			matrix.transform(x, y);
		} else {
			px = x;
			py = y;
		}

		if (py === polyline[polyline.length - 1] && py === polyline[polyline.length - 3]) polyline[polyline.length - 2] = px; else if (px === polyline[polyline.length - 2] && px === polyline[polyline.length - 4]) polyline[polyline.length - 1] = py; else polyline.push(px, py);
	};

	var _line = function line(x0, y0, x1, y1) {
		if (x0 !== px || y0 !== py) moveTo(x0, y0);
		lineTo(x1, y1);
	};

	var _rect = function rect(x0, y0, w0, h0) {
		moveTo(x0, y0);
		lineTo(x0 + w0, y0);
		lineTo(x0 + w0, y0 + h0);
		lineTo(x0, y0 + h0);
		lineTo(x0, y0);
	};

	var _fillRect = function fillRect(x0, y0, w0, h0) {
		var s = opt.strokeWidth || 0.2;

		for (var x = x0; x <= x0 + w0; x += s) {
			moveTo(x, y0);
			lineTo(x, y0 + h0);
		}
	};

	var quadraticCurveTo = function quadraticCurveTo(cx, cy, x1, y1, steps) {
		if (steps === void 0) {
			steps = 20;
		}

		var s = 1 / steps;
		var x0 = px;
		var y0 = py;

		for (var t = 0; t < 1; t += s) {
			lineTo((1 - t) * (1 - t) * x0 + 2 * (1 - t) * t * cx + t * t * x1, (1 - t) * (1 - t) * y0 + 2 * (1 - t) * t * cy + t * t * y1);
		}

		lineTo(x1, y1);
	};

	var ellipse = function ellipse(x, y, w, h, start, end) {
		if (h === void 0) {
			h = w;
		}

		if (start === void 0) {
			start = 0;
		}

		if (end === void 0) {
			end = 2 * Math.PI;
		}

		var steps = Math.PI / 36;
		moveTo(x + Math.cos(start) * w * 0.5, y);

		for (var a = start + steps; a <= end; a += steps) {
			lineTo(x + Math.cos(a) * w * 0.5, y - Math.sin(a) * h * 0.5);
		}
	};

	var canvas = function canvas(w, h) {
		var canvas = document.createElement("canvas");
		canvas.width = w;
		canvas.height = h;
		return canvas;
	}; ////////////////////////////////////////////////////////////////
	// https://turtletoy.net/turtle/25b7bc4d43
	// Reinder's occlusion code parts from "Cubic space division #2"
	////////////////////////////////////////////////////////////////


	var Polygons = /*#__PURE__*/function () {
		function Polygons() {
			this.list = [];
		}

		var _proto = Polygons.prototype;

		_proto.draw = function draw(p) {
			var vis = true;

			for (var _iterator = _createForOfIteratorHelperLoose(this.list), _step; !(_step = _iterator()).done;) {
				var p1 = _step.value;

				// AABB overlapping test - still O(N2) but very fast
				if (Math.abs(p1.aabb[0] - p.aabb[0]) - (p.aabb[2] + p1.aabb[2]) < 0 && Math.abs(p1.aabb[1] - p.aabb[1]) - (p.aabb[3] + p1.aabb[3]) < 0) {
					if (p.boolean(p1) === false) {
						vis = false;
						break;
					}
				}
			}

			if (vis === true) {
				p.draw();
				this.list.push(p);
			}			
		};

		_proto.create = function create() {
			return new Polygon();
		};

		_proto.clear = function clear() {
			this.list.length = 0;
		};

		return Polygons;
	}();

	var Polygon = /*#__PURE__*/function () {
		function Polygon() {
			this.cp = [];
			this.dp = [];
			this.aabb = [];
		}

		var _proto2 = Polygon.prototype;

		_proto2.addPoints = function addPoints() {
			for (var i = 0; i < arguments.length; i++) {
				this.cp.push(i < 0 || arguments.length <= i ? undefined : arguments[i]);
			}

			this.aabb = this.AABB();
		};

		_proto2.addOutline = function addOutline(s, e) {
			if (s === void 0) {
				s = 0;
			}

			if (e === void 0) {
				e = this.cp.length;
			}

			var len = this.cp.length;

			for (var i = s; i < e; i++) {
				this.dp.push(this.cp[i], this.cp[(i + 1) % len]);
			}
		};

		_proto2.draw = function draw() {
			if (this.dp.length === 0) return;

			for (var i = 0, l = this.dp.length; i < l; i += 2) {
				var d0 = this.dp[i];
				var d1 = this.dp[i + 1];
				moveTo(d0[0], d0[1]);
				lineTo(d1[0], d1[1]);
			}
		};

		_proto2.AABB = function AABB() {
			var xmin = 2000;
			var xmax = -2000;
			var ymin = 2000;
			var ymax = -2000;

			for (var _iterator2 = _createForOfIteratorHelperLoose(this.cp), _step2; !(_step2 = _iterator2()).done;) {
				var cp = _step2.value;
				var x = cp[0];
				var y = cp[1];
				if (x < xmin) xmin = x;
				if (x > xmax) xmax = x;
				if (y < ymin) ymin = y;
				if (y > ymax) ymax = y;
			}

			return [(xmin + xmax) * 0.5, (ymin + ymax) * 0.5, (xmax - xmin) * 0.5, (ymax - ymin) * 0.5];
		};

		_proto2.addHatching = function addHatching(a, d) {
			var tp = new Polygon();
			var x = this.aabb[0],
				y = this.aabb[1];
			var w = this.aabb[2],
				h = this.aabb[3];
			var l = Math.sqrt((w * 2) ** 2 + (h * 2) ** 2) * 0.5;
			tp.cp.push([x - w, y - h], [x + w, y - h], [x + w, y + h], [x - w, y + h]);
			var cx = Math.sin(a) * l,
				cy = Math.cos(a) * l;
			var px = x - Math.cos(a) * l;
			var py = y - Math.sin(a) * l;

			for (var i = 0; i < l * 2; i += d) {
				tp.dp.push([px + cx, py - cy], [px - cx, py + cy]);
				px += Math.cos(a) * d;
				py += Math.sin(a) * d;
			}

			tp.boolean(this, 1);

			for (var _iterator3 = _createForOfIteratorHelperLoose(tp.dp), _step3; !(_step3 = _iterator3()).done;) {
				var dp = _step3.value;
				this.dp.push(dp);
			}
		};

		_proto2.inside = function inside(p) {
			var int = 0;
			var px = p[0];
			var py = p[1];

			for (var i = 0, l = this.cp.length; i < l; i++) {
				if ((px - this.cp[i][0]) ** 2 + (py - this.cp[i][1]) ** 2 <= 0.01) return 0;
				if (Polygon.intersect([], p, [0.1, -1000], this.cp[i], this.cp[(i + 1) % l]) === true) int++;
			}

			return int & 1;
		};

		_proto2.boolean = function boolean(p, diff) {
			if (diff === void 0) {
				diff = 0;
			}

			var ndp = [],
				pint = [0, 0];

			for (var i = 0, l = this.dp.length; i < l; i += 2) {
				var ls0 = this.dp[i];
				var ls1 = this.dp[i + 1];
				var int = [];

				for (var j = 0, cl = p.cp.length; j < cl; j++) {
					if (Polygon.intersect(pint, ls0, ls1, p.cp[j], p.cp[(j + 1) % cl]) === true) int.push([pint[0], pint[1]]);
				}

				if (int.length === 0) {
					if (diff === p.inside(ls0)) ndp.push(ls0, ls1);
				} else {
					int.push(ls0, ls1);
					var cx = ls1[0] - ls0[0];
					var cy = ls1[1] - ls0[1];

					for (var _i = 0, len = int.length; _i < len; _i++) {
						var _j = _i;
						var item = int[_j];

						for (var db = (item[0] - ls0[0]) * cx + (item[1] - ls0[1]) * cy; _j > 0 && (int[_j - 1][0] - ls0[0]) * cx + (int[_j - 1][1] - ls0[1]) * cy < db; _j--) {
							int[_j] = int[_j - 1];
						}

						int[_j] = item;
					}

					for (var _j2 = 0; _j2 < int.length - 1; _j2++) {
						if ((int[_j2][0] - int[_j2 + 1][0]) ** 2 + (int[_j2][1] - int[_j2 + 1][1]) ** 2 >= 0.01) {
							if (diff === p.inside([(int[_j2][0] + int[_j2 + 1][0]) / 2, (int[_j2][1] + int[_j2 + 1][1]) / 2])) ndp.push(int[_j2], int[_j2 + 1]);
						}
					}
				}
			}

			this.dp = ndp;
			return this.dp.length > 0;
		} // port of http://paulbourke.net/geometry/pointlineplane/Helpers.cs
			;

		Polygon.intersect = function intersect(pint, a, b, c, d) {
			var e = (d[1] - c[1]) * (b[0] - a[0]) - (d[0] - c[0]) * (b[1] - a[1]);
			if (e === 0) return false;
			var ua = ((d[0] - c[0]) * (a[1] - c[1]) - (d[1] - c[1]) * (a[0] - c[0])) / e;
			var ub = ((b[0] - a[0]) * (a[1] - c[1]) - (b[1] - a[1]) * (a[0] - c[0])) / e;

			if (ua >= 0 && ua <= 1 && ub >= 0 && ub <= 1) {
				pint[0] = a[0] + ua * (b[0] - a[0]);
				pint[1] = a[1] + ua * (b[1] - a[1]);
				return true;
			}

			return false;
		};

		return Polygon;
	}(); /////////////////////////////////////////////////////////////////////////////
	// adapted from https://turtletoy.net/js/turtlesvg.js


	var clip = function clip(polyline, left, size) {
		var pint = [0, 0];
		var clip = [left, left, left, size, size, size, size, left, left, left];
		var nps = [];
		var np = [];
		var pcx = polyline[0];
		var pcy = polyline[1];
		var inside = pcx > left && pcx < size && pcy > left && pcy < size;
		if (inside === true) np.push(pcx, pcy);

		for (var j = 0; j < polyline.length; j += 2) {
			var cx = polyline[j];
			var cy = polyline[j + 1];
			if (cx === pcx && cy === pcy && j < polyline.length - 2) continue;

			if (cx > left && cx < size && cy > left && cy < size) {
				if (inside) np.push(cx, cy); else {
					for (var i = 0; i < 8; i += 2) {
						if (Polygon.intersect(pint, [pcx, pcy], [cx, cy], [clip[i], clip[i + 1]], [clip[i + 2], clip[i + 3]]) === true) break;
					}

					np.push(pint[0], pint[1], cx, cy);
				}
				inside = true;
			} else {
				if (inside) {
					for (var _i2 = 0; _i2 < 8; _i2 += 2) {
						if (Polygon.intersect(pint, [pcx, pcy], [cx, cy], [clip[_i2], clip[_i2 + 1]], [clip[_i2 + 2], clip[_i2 + 3]]) === true) break;
					}

					np.push(pint[0], pint[1]);
					nps.push(np);
					np = [];
				} else {
					var ips = [];

					for (var _i3 = 0; _i3 < 8; _i3 += 2) {
						if (Polygon.intersect(pint, [pcx, pcy], [cx, cy], [clip[_i3], clip[_i3 + 1]], [clip[_i3 + 2], clip[_i3 + 3]]) === true) ips.push(pint[0], pint[1]);
					}

					if (ips.length === 4) nps.push(ips);
				}

				inside = false;
			}

			pcx = cx;
			pcy = cy;
		}

		if (np.length > 0) nps.push(np);
		return nps;
	};

	var polylineSVG = function polylineSVG(reduce) {
		if (reduce === void 0) {
			reduce = false;
		}

		if (polyline.length <= 2) {
			polyline = [];
			return;
		}

		var points = reduce ? polyline.map(function (n) {
			return n * 0.95 + offset + 5;
		}) : polyline;
		var clippedPaths = target === inside ? clip(points, 5, 195) : [points];

		for (var _iterator4 = _createForOfIteratorHelperLoose(clippedPaths), _step4; !(_step4 = _iterator4()).done;) {
			var _points = _step4.value;
			var poly = document.createElementNS("http://www.w3.org/2000/svg", "polyline");

			var svgPoints = _points.map(function (n) {
				return +n.toFixed(2);
			});

			poly.setAttribute("points", svgPoints.toString());
			target.appendChild(poly);
			if (target === inside) polylines.push(_points);
		}

		polyline = [];
	};

	var render = function render() {
		var start = performance.now();
		var run;		
		do {
			run = draw(iter++);
		} while (run === true && performance.now() - start < cpuTime);

		if (run === true) requestAnimationFrame(render); else {
			moveTo(px, py);
			iter = 0;
			matrixTransform = false;
			// target = outside;
			// target.innerHTML = "";
			polyline = [];
		}
		// Program is finished when run is false.
		// The run variable keeps track of whether the program is still drawing.
		if (run === false) {
			terrainLoaded = true;
		}

	}; //////////////////////////////////////////////////////


	var ContextFree = /*#__PURE__*/function () {
		function ContextFree() {
			var _this = this;

			this.minSize = 0.02;
			this.polygons = null;
			this.polygon = {};

			this.polygon.rect = function (m, a, s) {
				if (a === void 0) {
					a = 0;
				}

				if (s === void 0) {
					s = 0;
				}

				return _this.polyRect(m, a, s);
			};

			this.init();
		}

		var _proto3 = ContextFree.prototype;

		_proto3.init = function init() {
			this.shapes = [];
			this.zoom = 1;
			this.ox = 0;
			this.oy = 0;
			this.box = [0, 0, 0, 0];
			if (this.polygons !== null) this.polygons.clear();
		};

		_proto3.matrix = function matrix() {
			return new Mat2D([1, 0, 0, -1, 0, 0]);
		};

		_proto3.boundingBox = function boundingBox(m) {
			var p0 = this.transform(0, 0, m.m);
			var p1 = this.transform(0.5, 0, m.m);
			var p2 = this.transform(0.5, 0.5, m.m);
			var p3 = this.transform(0, 0.5, m.m);
			var minx = Math.min(p0[0], p1[0], p2[0], p3[0]);
			var maxx = Math.max(p0[0], p1[0], p2[0], p3[0]);
			var miny = Math.min(p0[1], p1[1], p2[1], p3[1]);
			var maxy = Math.max(p0[1], p1[1], p2[1], p3[1]);
			if (minx < this.box[0]) this.box[0] = minx; else if (maxx > this.box[2]) this.box[2] = maxx;
			if (miny < this.box[1]) this.box[1] = miny; else if (maxy > this.box[3]) this.box[3] = maxy;
		};

		_proto3.fillCircle = function fillCircle(m) {
			this.boundingBox(m);
			m.type = 0;
			this.shapes.push(m);
		};

		_proto3.circle = function circle(m) {
			this.boundingBox(m);
			m.type = 4;
			this.shapes.push(m);
		};

		_proto3.fillRect = function fillRect(m) {
			this.boundingBox(m);
			m.type = 2;
			this.shapes.push(m);
		};

		_proto3.rect = function rect(m) {
			this.boundingBox(m);
			m.type = 1;
			this.shapes.push(m);
		};

		_proto3.line = function line(m) {
			this.boundingBox(m);
			m.type = 3;
			this.shapes.push(m);
		};

		_proto3.polyRect = function polyRect(m, a, s) {
			if (a === void 0) {
				a = 0;
			}

			if (s === void 0) {
				s = 0;
			}

			this.boundingBox(m);
			m.type = 11;
			m.a = a;
			m.s = s;
			this.shapes.push(m);
		};

		_proto3.draw = function draw(shape) {
			var d, s;

			switch (shape.type) {
				case 0:
					// fillCircle
					s = 0.2 / shape.size();
					moveTo(this.transform(0.5, 0, shape.m));
					d = 1;

					for (var a = 0; a < Math.PI; a += s) {
						lineTo(this.transform(0.5 * Math.cos(a) * d, 0.5 * Math.sin(a) * d, shape.m));
						lineTo(this.transform(-0.5 * Math.cos(a) * d, -0.5 * Math.sin(a) * d, shape.m));
						d = -d;
					}

					break;

				case 1:
					// strokeRect
					moveTo(this.transform(-0.5, -0.5, shape.m));
					lineTo(this.transform(0.5, -0.5, shape.m));
					lineTo(this.transform(0.5, 0.5, shape.m));
					lineTo(this.transform(-0.5, 0.5, shape.m));
					lineTo(this.transform(-0.5, -0.5, shape.m));
					break;

				case 2:
					// fillRect
					moveTo(this.transform(-0.5, -0.5, shape.m));
					d = 1;
					s = 0.08 / shape.size();

					for (var y = -0.5; y <= 0.5; y += s) {
						lineTo(this.transform(-0.5 * d, y, shape.m));
						lineTo(this.transform(0.5 * d, y, shape.m));
						d = -d;
					}

					break;

				case 3:
					// line
					moveTo(this.transform(-0.5, 0, shape.m));
					lineTo(this.transform(0.5, 0, shape.m));
					break;

				case 4:
					// circle
					s = 0.1 / shape.size();
					moveTo(this.transform(0.5, 0, shape.m));

					for (var _a = 0; _a < 2 * Math.PI + s; _a += s) {
						lineTo(this.transform(0.5 * Math.cos(_a), 0.5 * Math.sin(_a), shape.m));
					}

					break;

				case 11:
					// polygon rectangle
					if (this.polygons === null) this.polygons = new Polygons();
					var p = this.polygons.create();
					var p0 = this.transform(-0.5, -0.5, shape.m);
					var p1 = this.transform(0.5, -0.5, shape.m);
					var p2 = this.transform(0.5, 0.5, shape.m);
					var p3 = this.transform(-0.5, 0.5, shape.m);
					p.addPoints(p0, p1, p2, p3);
					p.addOutline(0);

					if (shape.s !== 0) {
						if (shape.a === -1) {
							shape.a = Math.atan2(p1[1] - p2[1], p1[0] - p2[0]);
						}

						p.addHatching(shape.a, shape.s);
					}

					this.polygons.draw(p);					
					break;
			}
		};

		_proto3.scale = function scale(margin) {
			if (margin === void 0) {
				margin = 0.95;
			}

			this.zoom = Math.min(margin * 200 / (this.box[2] - this.box[0]), margin * 200 / (this.box[3] - this.box[1]));
			this.ox = (this.box[0] + this.box[2]) * 0.5 * this.zoom;
			this.oy = (this.box[3] + this.box[1]) * 0.5 * this.zoom;
		};

		_proto3.transform = function transform(x, y, m) {
			var m0 = m[0] * this.zoom;
			var m1 = m[1] * this.zoom;
			var m2 = m[2] * this.zoom;
			var m3 = m[3] * this.zoom;
			var m4 = m[4] * this.zoom - this.ox;
			var m5 = m[5] * this.zoom - this.oy;
			return [m0 * x + m2 * y + m4, m1 * x + m3 * y + m5];
		};
		
		return ContextFree;
	}();

	var Mat2D = /*#__PURE__*/function () {
		function Mat2D(m) {
			this.m = m;
			this.type = 0;
			this.a = 0;
			this.s = 0;
		}

		var _proto4 = Mat2D.prototype;

		_proto4.rotate = function rotate(v) {
			var rad = Math.PI * v / 180;
			var cos = Math.cos(rad);
			var sin = Math.sin(rad);
			return new Mat2D([cos * this.m[0] + sin * this.m[2], cos * this.m[1] + sin * this.m[3], cos * this.m[2] - sin * this.m[0], cos * this.m[3] - sin * this.m[1], this.m[4], this.m[5]]);
		};

		_proto4.translate = function translate(x, y) {
			if (y === void 0) {
				y = 0;
			}

			return new Mat2D([this.m[0], this.m[1], this.m[2], this.m[3], this.m[4] + x * this.m[0] + y * this.m[2], this.m[5] + x * this.m[1] + y * this.m[3]]);
		};

		_proto4.scale = function scale(x, y) {
			if (x === void 0) {
				x = 1;
			}

			if (y === void 0) {
				y = x;
			}

			return new Mat2D([this.m[0] * x, this.m[1] * x, this.m[2] * y, this.m[3] * y, this.m[4], this.m[5]]);
		};

		_proto4.flip = function flip(v) {
			var rad = Math.PI * v / 180;
			var x = Math.cos(rad);
			var y = Math.sin(rad);
			var n = 1 / (x * x + y * y);
			var b00 = (x * x - y * y) / n;
			var b01 = 2 * x * y / n;
			var b10 = 2 * x * y / n;
			var b11 = (y * y - x * x) / n;
			return new Mat2D([b00 * this.m[0] + b01 * this.m[2], b00 * this.m[1] + b01 * this.m[3], b10 * this.m[0] + b11 * this.m[2], b10 * this.m[1] + b11 * this.m[3], this.m[4], this.m[5]]);
		};

		_proto4.size = function size() {
			var x = this.m[0] * this.m[0] + this.m[1] * this.m[1];
			var y = this.m[2] * this.m[2] + this.m[3] * this.m[3];
			return Math.sqrt(Math.max(x, y));
		};

		return Mat2D;
	}(); ///////////////////////////////////////////////////////////////////////////


	var matrix = {
		data: [1, 0, 0, 1, 0, 0],
		stack: [],
		transform: function transform(x, y) {
			var m = this.data;
			px = m[0] * x + m[2] * y + m[4];
			py = m[1] * x + m[3] * y + m[5];
		},
		rotate: function rotate(v) {
			var m = this.data;
			matrixTransform = true;
			var cos = Math.cos(v);
			var sin = Math.sin(v);
			var r00 = cos * m[0] + sin * m[2];
			var r01 = cos * m[1] + sin * m[3];
			m[2] = cos * m[2] - sin * m[0];
			m[3] = cos * m[3] - sin * m[1];
			m[0] = r00;
			m[1] = r01;
			return this;
		},
		translate: function translate(x, y) {
			if (y === void 0) {
				y = 0;
			}

			var m = this.data;
			matrixTransform = true;
			m[4] += x * m[0] + y * m[2];
			m[5] += x * m[1] + y * m[3];
			return this;
		},
		scale: function scale(x, y) {
			if (x === void 0) {
				x = 1;
			}

			if (y === void 0) {
				y = x;
			}

			var m = this.data;
			matrixTransform = true;
			m[0] *= x;
			m[1] *= x;
			m[2] *= y;
			m[3] *= y;
			return this;
		},
		push: function push() {
			var m = this.data;
			this.stack.push([m[0], m[1], m[2], m[3], m[4], m[5]]);
			return this;
		},
		pop: function pop() {
			this.data = this.stack.pop();
			return this;
		}
	}; //////////////////////////////////////////////////////
	// http://mrl.nyu.edu/~perlin/noise/

	var Perlin = /*#__PURE__*/function () {
		function Perlin(setup) {
			this.p = new Uint8Array(512);
			this.octaves = setup.octaves || 1;
			this.init();
		}

		var _proto5 = Perlin.prototype;

		_proto5.init = function init() {
			var p = new Uint8Array(256);

			for (var i = 0; i < 256; i++) {
				p[i] = i;
			}

			for (var _i4 = 255; _i4 > 0; _i4--) {
				var n = Math.floor((_i4 + 1) * Math.random());
				var q = p[_i4];
				p[_i4] = p[n];
				p[n] = q;
			}

			for (var _i5 = 0; _i5 < 512; _i5++) {
				this.p[_i5] = p[_i5 & 255];
			}
		};

		_proto5.lerp = function lerp(t, a, b) {
			return a + t * (b - a);
		};

		_proto5.grad2d = function grad2d(i, x, y) {
			var v = (i & 1) === 0 ? x : y;
			return (i & 2) === 0 ? -v : v;
		};

		_proto5.noise2d = function noise2d(x2d, y2d) {
			var X = Math.floor(x2d) & 255;
			var Y = Math.floor(y2d) & 255;
			var x = x2d - Math.floor(x2d);
			var y = y2d - Math.floor(y2d);
			var fx = (3 - 2 * x) * x * x;
			var fy = (3 - 2 * y) * y * y;
			var p0 = this.p[X] + Y;
			var p1 = this.p[X + 1] + Y;
			return this.lerp(fy, this.lerp(fx, this.grad2d(this.p[p0], x, y), this.grad2d(this.p[p1], x - 1, y)), this.lerp(fx, this.grad2d(this.p[p0 + 1], x, y - 1), this.grad2d(this.p[p1 + 1], x - 1, y - 1)));
		};

		_proto5.noise = function noise(x, y) {
			var e = 1,
				k = 1,
				s = 0;

			for (var i = 0; i < this.octaves; ++i) {
				e *= 0.5;
				s += e * (1 + this.noise2d(k * x, k * y)) / 2;
				k *= 2;
			}

			return s;
		};

		return Perlin;
	}(); // https://cdnjs.cloudflare.com/ajax/libs/simplex-noise/2.4.0/simplex-noise.js


	var Simplex = /*#__PURE__*/function () {
		function Simplex(setup) {
			this.octaves = setup.octaves || 1;
			this.F2 = 0.5 * (Math.sqrt(3.0) - 1.0);
			this.G2 = (3.0 - Math.sqrt(3.0)) / 6.0;
			this.p = new Uint8Array(256);
			this.grad3 = new Float32Array([1, 1, 0, -1, 1, 0, 1, -1, 0, -1, -1, 0, 1, 0, 1, -1, 0, 1, 1, 0, -1, -1, 0, -1, 0, 1, 1, 0, -1, 1, 0, 1, -1, 0, -1, -1]);
			this.init();
		}

		var _proto6 = Simplex.prototype;

		_proto6.init = function init() {
			for (var i = 0; i < 256; i++) {
				this.p[i] = i;
			}

			for (var _i6 = 0; _i6 < 255; _i6++) {
				var r = _i6 + ~~(Math.random() * (256 - _i6));
				var aux = this.p[_i6];
				this.p[_i6] = this.p[r];
				this.p[r] = aux;
			}

			this.perm = new Uint8Array(512);
			this.permMod12 = new Uint8Array(512);

			for (var _i7 = 0; _i7 < 512; _i7++) {
				this.perm[_i7] = this.p[_i7 & 255];
				this.permMod12[_i7] = this.perm[_i7] % 12;
			}
		};

		_proto6.noise2D = function noise2D(xin, yin) {
			var n0 = 0,
				n1 = 0,
				n2 = 0;
			var s = (xin + yin) * this.F2;
			var i = Math.floor(xin + s);
			var j = Math.floor(yin + s);
			var t = (i + j) * this.G2;
			var X0 = i - t;
			var Y0 = j - t;
			var x0 = xin - X0;
			var y0 = yin - Y0;
			var i1 = x0 > y0 ? 1 : 0;
			var j1 = x0 > y0 ? 0 : 1;
			var x1 = x0 - i1 + this.G2;
			var y1 = y0 - j1 + this.G2;
			var x2 = x0 - 1.0 + 2.0 * this.G2;
			var y2 = y0 - 1.0 + 2.0 * this.G2;
			var ii = i & 255;
			var jj = j & 255;
			var t0 = 0.5 - x0 * x0 - y0 * y0;

			if (t0 >= 0) {
				var gi0 = this.permMod12[ii + this.perm[jj]] * 3;
				t0 *= t0;
				n0 = t0 * t0 * (this.grad3[gi0] * x0 + this.grad3[gi0 + 1] * y0);
			}

			var t1 = 0.5 - x1 * x1 - y1 * y1;

			if (t1 >= 0) {
				var gi1 = this.permMod12[ii + i1 + this.perm[jj + j1]] * 3;
				t1 *= t1;
				n1 = t1 * t1 * (this.grad3[gi1] * x1 + this.grad3[gi1 + 1] * y1);
			}

			var t2 = 0.5 - x2 * x2 - y2 * y2;

			if (t2 >= 0) {
				var gi2 = this.permMod12[ii + 1 + this.perm[jj + 1]] * 3;
				t2 *= t2;
				n2 = t2 * t2 * (this.grad3[gi2] * x2 + this.grad3[gi2 + 1] * y2);
			}

			return 70.0 * (n0 + n1 + n2);
		};

		_proto6.noise = function noise(x, y) {
			var e = 1,
				k = 1,
				s = 0;

			for (var i = 0; i < this.octaves; ++i) {
				e *= 0.5;
				s += e * (1 + this.noise2D(k * x, k * y)) / 2;
				k *= 2;
			}

			return s;
		};

		return Simplex;
	}(); //////////////////////////////////////////////////////
	
	var imageData = function imageData(img, width, height) {

		if (width === void 0) {
			width = 0;
		}

		if (height === void 0) {
			height = 0;
		}

		if (typeof img === "string") img = document.getElementById(img);
		var canvas = document.createElement("canvas");
		var w = canvas.width = width || img.width;
		var h = canvas.height = height || img.height;
		var ctx = canvas.getContext("2d");
		ctx.drawImage(img, 0, 0, w, h);
		return ctx.getImageData(0, 0, w, h);
	}; /////////////////////////////////////////////////	
	return {
		simplex: function simplex(opt) {
			return new Simplex(opt || {});
		},
		perlin: function perlin(opt) {
			return new Perlin(opt || {});
		},
		getX: function getX() {
			return px;
		},
		getY: function getY() {
			return py;
		},
		moveTo: moveTo,
		lineTo: lineTo,
		quadraticCurveTo: quadraticCurveTo,
		ellipse: ellipse,
		circle: function circle(x, y, w) {
			ellipse(x, y, w, w, 0, 2 * Math.PI);
		},
		arc: function arc(x, y, w, s, e) {
			ellipse(x, y, w, w, s, e);
		},
		fillRect: function fillRect(x0, y0, w0, h0) {
			if (h0 === void 0) {
				h0 = w0;
			}

			_fillRect(x0, y0, w0, h0);
		},
		rect: function rect(x0, y0, w0, h0) {
			if (h0 === void 0) {
				h0 = w0;
			}

			_rect(x0, y0, w0, h0);
		},
		line: function line(x0, y0, x1, y1) {
			if (Array.isArray(x0)) {
				if (Array.isArray(y0)) _line(x0[0], x0[1], y0[0], y0[1]); else _line(x0[0], x0[1], y0, x1);
			} else _line(x0, y0, x1, y1);
		},
		polygons: function polygons() {
			return new Polygons();
		},
		start: start,
		run: start,
		matrix: matrix,
		imageData: imageData,
		canvas: canvas,
		contextFree: function contextFree() {			
			return new ContextFree();
		}
	};
};