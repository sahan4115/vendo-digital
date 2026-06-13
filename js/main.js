/* ════════════════════════════════════════════════════════════
   VENDO. — main.js
   Preloader · custom cursor · magnetic UI · GSAP scroll story ·
   Three.js particle field · services accordion · tilt cards
   ════════════════════════════════════════════════════════════ */
(function () {
  "use strict";

  var prefersReduced = window.matchMedia("(prefers-reduced-motion: reduce)").matches;
  var finePointer = window.matchMedia("(pointer: fine)").matches;
  var hasGSAP = typeof window.gsap !== "undefined";
  var hasThree = typeof window.THREE !== "undefined";

  // The preloader intro plays from the top; letting the browser restore a
  // mid-page scroll position would skip it and skew ScrollTrigger's
  // measurements taken during load.
  if ("scrollRestoration" in history) history.scrollRestoration = "manual";
  try { window.scrollTo({ top: 0, behavior: "instant" }); } catch (e) { window.scrollTo(0, 0); }

  /* ── Graceful fallback if CDNs fail ───────── */
  if (!hasGSAP) {
    document.documentElement.classList.add("no-gsap");
    document.querySelectorAll(".hero-title .word, .pre-logo span").forEach(function (el) {
      el.style.transform = "none";
    });
    var pre = document.getElementById("preloader");
    if (pre) pre.remove();
    document.body.removeAttribute("data-loading");
  } else {
    gsap.registerPlugin(ScrollTrigger);
  }

  /* ════════ THREE.JS — breathing particle field ════════
     A plane of points displaced by layered sine waves,
     tinted Vendo Green, with gentle mouse parallax.       */
  (function initWebGL() {
    var canvas = document.getElementById("webgl");
    if (!canvas || !hasThree || prefersReduced) return;

    var renderer;
    try {
      renderer = new THREE.WebGLRenderer({ canvas: canvas, antialias: false, alpha: true, powerPreference: "high-performance" });
    } catch (e) { return; }

    var DPR = Math.min(window.devicePixelRatio || 1, 1.75);
    renderer.setPixelRatio(DPR);

    var scene = new THREE.Scene();
    scene.fog = new THREE.FogExp2(0x051412, 0.055);

    var camera = new THREE.PerspectiveCamera(60, 1, 0.1, 100);
    camera.position.set(0, 3.2, 9);
    camera.lookAt(0, 0, 0);

    // Fewer particles on small screens to protect mobile frame rate
    var isSmall = window.innerWidth < 760;
    var COLS = isSmall ? 90 : 160;
    var ROWS = isSmall ? 60 : 100;
    var W = 30, D = 20;

    var count = COLS * ROWS;
    var positions = new Float32Array(count * 3);
    var seeds = new Float32Array(count);
    var i = 0, ix, iz;
    for (ix = 0; ix < COLS; ix++) {
      for (iz = 0; iz < ROWS; iz++) {
        positions[i * 3] = (ix / (COLS - 1) - 0.5) * W;
        positions[i * 3 + 1] = 0;
        positions[i * 3 + 2] = (iz / (ROWS - 1) - 0.5) * D;
        seeds[i] = Math.random();
        i++;
      }
    }

    var geo = new THREE.BufferGeometry();
    geo.setAttribute("position", new THREE.BufferAttribute(positions, 3));
    geo.setAttribute("aSeed", new THREE.BufferAttribute(seeds, 1));

    var mat = new THREE.ShaderMaterial({
      transparent: true,
      depthWrite: false,
      blending: THREE.AdditiveBlending,
      uniforms: {
        uTime: { value: 0 },
        uMouse: { value: new THREE.Vector2(0, 0) },
        uPixelRatio: { value: DPR }
      },
      vertexShader: [
        "uniform float uTime;",
        "uniform vec2 uMouse;",
        "uniform float uPixelRatio;",
        "attribute float aSeed;",
        "varying float vGlow;",
        "void main() {",
        "  vec3 p = position;",
        "  float t = uTime * 0.6;",
        "  float wave = sin(p.x * 0.55 + t) * 0.55",
        "             + sin(p.z * 0.85 + t * 1.3) * 0.4",
        "             + sin((p.x + p.z) * 0.32 + t * 0.7) * 0.55;",
        "  p.y += wave;",
        "  p.y += sin(aSeed * 6.2831 + t * 2.0) * 0.07;",
        "  p.x += uMouse.x * (1.0 - abs(p.z) / 10.0) * 1.4;",
        "  p.y += uMouse.y * 0.6;",
        "  vec4 mv = modelViewMatrix * vec4(p, 1.0);",
        "  gl_Position = projectionMatrix * mv;",
        "  vGlow = smoothstep(-1.4, 1.6, wave) * (0.55 + aSeed * 0.45);",
        "  gl_PointSize = (1.4 + vGlow * 2.4) * uPixelRatio * (9.0 / -mv.z);",
        "}"
      ].join("\n"),
      fragmentShader: [
        "varying float vGlow;",
        "void main() {",
        "  float d = length(gl_PointCoord - 0.5);",
        "  if (d > 0.5) discard;",
        "  float alpha = smoothstep(0.5, 0.05, d) * (0.18 + vGlow * 0.6);",
        "  vec3 green = vec3(0.557, 0.996, 0.733);", // #8EFEBB
        "  vec3 sage  = vec3(0.10, 0.36, 0.30);",
        "  gl_FragColor = vec4(mix(sage, green, vGlow), alpha);",
        "}"
      ].join("\n")
    });

    scene.add(new THREE.Points(geo, mat));

    var mouse = { x: 0, y: 0, tx: 0, ty: 0 };
    window.addEventListener("pointermove", function (e) {
      mouse.tx = (e.clientX / window.innerWidth - 0.5) * 2;
      mouse.ty = (e.clientY / window.innerHeight - 0.5) * 2;
    }, { passive: true });

    function resize() {
      var w = canvas.clientWidth || window.innerWidth;
      var h = canvas.clientHeight || window.innerHeight;
      renderer.setSize(w, h, false);
      camera.aspect = w / h;
      camera.updateProjectionMatrix();
    }
    resize();
    window.addEventListener("resize", resize, { passive: true });
    // Catches viewport changes that don't fire a window resize (emulation,
    // some orientation changes)
    if ("ResizeObserver" in window) new ResizeObserver(resize).observe(canvas);

    var clock = new THREE.Clock();
    var running = true;
    var heroVisible = true;

    // Stop rendering when the hero scrolls out or the tab hides
    if ("IntersectionObserver" in window) {
      new IntersectionObserver(function (entries) {
        heroVisible = entries[0].isIntersecting;
      }, { threshold: 0 }).observe(canvas);
    }
    document.addEventListener("visibilitychange", function () {
      running = !document.hidden;
    });

    (function tick() {
      requestAnimationFrame(tick);
      if (!running || !heroVisible) return;
      mouse.x += (mouse.tx - mouse.x) * 0.05;
      mouse.y += (mouse.ty - mouse.y) * 0.05;
      mat.uniforms.uTime.value = clock.getElapsedTime();
      mat.uniforms.uMouse.value.set(mouse.x, -mouse.y);
      renderer.render(scene, camera);
    })();
  })();

  if (!hasGSAP) return; // everything below depends on GSAP

  /* ════════ PRELOADER → HERO sequence ════════ */
  (function intro() {
    var preloader = document.getElementById("preloader");
    var countEl = document.getElementById("preCount");
    var heroWords = document.querySelectorAll(".hero-title .word");
    var extras = gsap.utils.toArray(".hero-eyebrow, .hero-sub, .btn-hero, .hero-scroll");
    var tl = gsap.timeline({ defaults: { ease: "expo.out" } });

    if (prefersReduced) {
      if (preloader) preloader.remove();
      document.body.removeAttribute("data-loading");
      gsap.set(heroWords, { y: 0 });
      if (extras.length) gsap.set(extras, { opacity: 1 });
      return;
    }

    // Inner pages have no preloader — play a fast page-enter instead
    // (the sage veil is CSS-only; this lifts the hero copy in under it).
    if (!preloader) {
      document.body.removeAttribute("data-loading");
      if (extras.length) gsap.set(extras, { opacity: 0, y: 24 });
      gsap.timeline({ defaults: { ease: "expo.out" }, delay: 0.2 })
        .to(heroWords, { y: 0, duration: 1.1, stagger: 0.08 })
        .to(extras, { opacity: 1, y: 0, duration: 0.7, stagger: 0.08 }, "-=0.7");
      return;
    }

    gsap.set(".hero-eyebrow, .hero-sub, .btn-hero, .hero-scroll", { opacity: 0, y: 24 });

    // Failsafe: rAF is suspended in background tabs, which would hold the
    // preloader forever. If the intro hasn't finished after 8s of wall time,
    // jump straight to the loaded state.
    setTimeout(function () {
      if (document.getElementById("preloader")) {
        tl.progress(1);
      }
    }, 8000);

    var counter = { v: 0 };
    tl.to(".pre-logo span", { y: 0, duration: 1, stagger: 0.06 })
      .to(".pre-meta", { opacity: 1, duration: 0.5 }, "-=0.5")
      .to(counter, {
        v: 100, duration: 1.4, ease: "power2.inOut",
        onUpdate: function () {
          if (countEl) countEl.textContent = String(Math.round(counter.v)).padStart(2, "0");
        }
      }, "-=0.6")
      .to(".pre-logo span", { y: "-110%", duration: 0.7, stagger: 0.04, ease: "expo.in" })
      .to(".pre-meta", { opacity: 0, duration: 0.3 }, "<")
      .to(".pre-veil", { scaleY: 1, duration: 0.55, ease: "expo.inOut" }, "-=0.35")
      .add(function () {
        document.body.removeAttribute("data-loading");
      })
      .to(preloader, {
        yPercent: -100, duration: 0.9, ease: "expo.inOut",
        onComplete: function () {
          preloader.remove();
          // re-measure pinned sections: the scrollbar only appears once
          // body[data-loading] overflow:hidden is lifted, changing widths
          ScrollTrigger.refresh();
        }
      })
      // hero reveal
      .to(heroWords, { y: 0, duration: 1.2, stagger: 0.09 }, "-=0.45")
      .to(".hero-eyebrow", { opacity: 1, y: 0, duration: 0.8 }, "-=0.9")
      .to(".hero-sub", { opacity: 1, y: 0, duration: 0.8 }, "-=0.7")
      .to(".btn-hero", { opacity: 1, y: 0, duration: 0.8 }, "-=0.65")
      .to(".hero-scroll", { opacity: 1, y: 0, duration: 0.8 }, "-=0.6");
  })();

  /* ════════ CUSTOM CURSOR ════════ */
  (function cursor() {
    if (!finePointer || prefersReduced) return;
    var el = document.querySelector(".cursor");
    if (!el) return;
    var label = el.querySelector(".cursor-text");

    var x = gsap.quickTo(el, "x", { duration: 0.35, ease: "power3.out" });
    var y = gsap.quickTo(el, "y", { duration: 0.35, ease: "power3.out" });
    window.addEventListener("pointermove", function (e) {
      x(e.clientX); y(e.clientY);
    }, { passive: true });

    document.querySelectorAll("[data-cursor]").forEach(function (target) {
      target.addEventListener("pointerenter", function () {
        label.textContent = target.getAttribute("data-cursor");
        el.classList.add("is-label");
      });
      target.addEventListener("pointerleave", function () {
        el.classList.remove("is-label");
      });
    });
  })();

  /* ════════ MAGNETIC ELEMENTS ════════ */
  (function magnetic() {
    if (!finePointer || prefersReduced) return;
    document.querySelectorAll("[data-magnetic]").forEach(function (el) {
      var xTo = gsap.quickTo(el, "x", { duration: 0.5, ease: "elastic.out(1, 0.5)" });
      var yTo = gsap.quickTo(el, "y", { duration: 0.5, ease: "elastic.out(1, 0.5)" });
      el.addEventListener("pointermove", function (e) {
        var r = el.getBoundingClientRect();
        xTo((e.clientX - r.left - r.width / 2) * 0.35);
        yTo((e.clientY - r.top - r.height / 2) * 0.35);
      });
      el.addEventListener("pointerleave", function () { xTo(0); yTo(0); });
    });
  })();

  /* ════════ NAV: shrink + hide on scroll down ════════ */
  (function nav() {
    var navEl = document.getElementById("nav");
    if (!navEl) return;
    var last = 0;
    window.addEventListener("scroll", function () {
      var yPos = window.scrollY;
      navEl.classList.toggle("is-scrolled", yPos > 40);
      if (yPos > 400 && yPos > last + 4) navEl.classList.add("is-hidden");
      else if (yPos < last - 4) navEl.classList.remove("is-hidden");
      last = yPos;
    }, { passive: true });
  })();

  /* ════════ FULLSCREEN MENU ════════ */
  (function menu() {
    var burger = document.getElementById("burger");
    var menuEl = document.getElementById("menu");
    if (!burger || !menuEl) return;

    function setOpen(open) {
      menuEl.classList.toggle("is-open", open);
      menuEl.setAttribute("aria-hidden", String(!open));
      burger.setAttribute("aria-expanded", String(open));
      burger.setAttribute("aria-label", open ? "Close menu" : "Open menu");
      document.body.style.overflow = open ? "hidden" : "";
      document.body.classList.toggle("menu-open", open);
    }
    burger.addEventListener("click", function () {
      setOpen(!menuEl.classList.contains("is-open"));
    });
    menuEl.querySelectorAll("a").forEach(function (a) {
      a.addEventListener("click", function () { setOpen(false); });
    });
    document.addEventListener("keydown", function (e) {
      if (e.key === "Escape") setOpen(false);
    });
  })();

  /* ════════ MARQUEE: infinite loop, speeds up with scroll ════════ */
  (function marquee() {
    var track = document.getElementById("marqueeTrack");
    if (!track || prefersReduced) return;
    var tween = gsap.to(track, { xPercent: -50, ease: "none", duration: 22, repeat: -1 });
    ScrollTrigger.create({
      onUpdate: function (self) {
        var v = Math.abs(self.getVelocity()) / 2200;
        gsap.to(tween, { timeScale: 1 + Math.min(v, 3), duration: 0.3, overwrite: true });
      }
    });
  })();

  /* ════════ SECTION REVEALS ════════ */
  (function reveals() {
    if (prefersReduced) return;
    var targets = [
      ".section-head", ".case", ".meta-card", ".fork-card",
      ".svc-detail", ".ccard", ".faq2-item", ".bento-tile", ".tstep",
      ".work-more", ".cta-title", ".btn-big", ".cta-note", ".cta-ring"
    ];
    document.querySelectorAll(targets.join(",")).forEach(function (el) {
      gsap.from(el, {
        opacity: 0, y: 56, duration: 1.1, ease: "expo.out",
        scrollTrigger: { trigger: el, start: "top 88%", once: true }
      });
    });

    // mark cases in view so the dashboard chart line draws itself
    document.querySelectorAll(".case").forEach(function (el) {
      ScrollTrigger.create({
        trigger: el, start: "top 70%", once: true,
        onEnter: function () { el.classList.add("in-view"); }
      });
    });
  })();

  /* ════════ MANIFESTO: words light up as you scroll ════════ */
  (function manifesto() {
    var el = document.getElementById("manifestoText");
    if (!el) return;
    // WordPress (wp_localize_script) can override the accent words via
    // a global VENDO.accents array; the literal is the static-site default.
    var accents = (window.VENDO && window.VENDO.accents && window.VENDO.accents.length)
      ? window.VENDO.accents
      : ["pdf", "pound", "deserve", "numbers", "read"];
    var words = el.textContent.trim().split(/\s+/);
    el.innerHTML = words.map(function (w) {
      var isAccent = accents.some(function (a) { return w.toLowerCase().indexOf(a) === 0; });
      return '<span class="w' + (isAccent ? " accent" : "") + '">' + w + "</span>";
    }).join(" ");

    var spans = el.querySelectorAll(".w");
    if (prefersReduced) {
      spans.forEach(function (s) { s.classList.add("lit"); });
      return;
    }
    ScrollTrigger.create({
      trigger: el,
      start: "top 80%",
      end: "bottom 45%",
      scrub: 0.4,
      onUpdate: function (self) {
        var n = Math.floor(self.progress * spans.length);
        spans.forEach(function (s, idx) { s.classList.toggle("lit", idx <= n); });
      }
    });
  })();

  /* ════════ STAT COUNTERS ════════ */
  (function counters() {
    document.querySelectorAll("[data-count]").forEach(function (el) {
      var target = parseInt(el.getAttribute("data-count"), 10);
      if (prefersReduced) { el.textContent = target; return; }
      var obj = { v: 0 };
      ScrollTrigger.create({
        trigger: el, start: "top 85%", once: true,
        onEnter: function () {
          gsap.to(obj, {
            v: target, duration: 1.8, ease: "power3.out",
            onUpdate: function () { el.textContent = Math.round(obj.v); }
          });
        }
      });
    });
  })();

  /* ════════ SERVICES SHOWCASE ════════
     Vertical tablist (left) drives a cross-fading preview stage (right).
     Hover previews on desktop; click / keyboard work everywhere.        */
  (function services() {
    var list = document.getElementById("svcList");
    if (!list) return;
    var items = Array.prototype.slice.call(list.querySelectorAll(".svc-item"));
    var panels = Array.prototype.slice.call(document.querySelectorAll(".svc-panel"));
    var marker = list.querySelector(".svc-marker");
    var watermark = document.getElementById("svcWatermark");
    var current = 0;
    // Live query so hover-preview tracks the actual viewport, not load-time width
    var hoverMQ = window.matchMedia("(min-width: 900px) and (hover: hover)");

    function moveMarker(item) {
      if (!marker) return;
      marker.style.height = item.offsetHeight + "px";
      marker.style.transform = "translateY(" + item.offsetTop + "px)";
      marker.classList.add("is-ready");
    }

    function setActive(index, focusTab) {
      if (index < 0) index = items.length - 1;
      if (index >= items.length) index = 0;
      current = index;
      items.forEach(function (it, i) {
        var on = i === index;
        it.classList.toggle("is-active", on);
        it.setAttribute("aria-selected", String(on));
        it.tabIndex = on ? 0 : -1;
        if (on && focusTab) it.focus();
      });
      panels.forEach(function (p, i) {
        var on = i === index;
        p.classList.toggle("is-active", on);
        if (on) p.removeAttribute("hidden"); else p.setAttribute("hidden", "");
      });
      if (watermark) watermark.textContent = String(index + 1).padStart(2, "0");
      moveMarker(items[index]);
    }

    items.forEach(function (item, i) {
      item.addEventListener("click", function () { setActive(i); });
      // Hover/focus preview — gated at event time so resizing across the
      // breakpoint (and mouse vs touch) is always respected.
      item.addEventListener("pointerenter", function (e) {
        if (hoverMQ.matches && e.pointerType !== "touch") setActive(i);
      });
      item.addEventListener("focus", function () {
        if (hoverMQ.matches) setActive(i);
      });
      // Roving-tabindex keyboard support for the vertical tablist
      item.addEventListener("keydown", function (e) {
        switch (e.key) {
          case "ArrowDown": case "ArrowRight": e.preventDefault(); setActive(i + 1, true); break;
          case "ArrowUp": case "ArrowLeft": e.preventDefault(); setActive(i - 1, true); break;
          case "Home": e.preventDefault(); setActive(0, true); break;
          case "End": e.preventDefault(); setActive(items.length - 1, true); break;
          case "Enter": case " ": e.preventDefault(); setActive(i); break;
        }
      });
    });

    // Initialise marker once fonts/layout settle, and keep it aligned on resize
    function sync() { moveMarker(items[current]); }
    if (document.fonts && document.fonts.ready) document.fonts.ready.then(sync);
    window.addEventListener("load", sync);
    window.addEventListener("resize", sync, { passive: true });
    requestAnimationFrame(sync);

    // Subtle parallax on the active visual as the pointer moves over the stage
    var stage = document.getElementById("svcStage");
    if (stage && !prefersReduced) {
      stage.addEventListener("pointermove", function (e) {
        if (!hoverMQ.matches || e.pointerType === "touch") return;
        var r = stage.getBoundingClientRect();
        var dx = (e.clientX - r.left) / r.width - 0.5;
        var dy = (e.clientY - r.top) / r.height - 0.5;
        var viz = stage.querySelector(".svc-panel.is-active .viz");
        if (viz) viz.style.transform = "translate(" + (dx * 16) + "px," + (dy * 16) + "px)";
      });
      stage.addEventListener("pointerleave", function () {
        var viz = stage.querySelector(".svc-panel.is-active .viz");
        if (viz) viz.style.transform = "";
      });
    }
  })();

  /* ════════ PROCESS — sticky word-stack ════════
     CSS position:sticky does the pinning (each .fstep wrapper is taller
     than the viewport, holding its panel on screen). GSAP scrubs the
     giant word's green fill across that hold and wakes each visual.    */
  (function flow() {
    var steps = Array.prototype.slice.call(document.querySelectorAll(".fstep"));
    if (!steps.length) return;

    var panels = steps.map(function (w) { return w.querySelector(".fpanel"); });

    steps.forEach(function (wrap, i) {
      var panel = panels[i];
      var fill = wrap.querySelector(".fword-fill");

      if (prefersReduced) {
        panel.classList.add("is-active");
        if (fill) fill.style.clipPath = "inset(-5% 0% -5% 0)";
        return;
      }

      // wake the visual + reveal the copy as the panel arrives
      ScrollTrigger.create({
        trigger: wrap, start: "top 60%", once: true,
        onEnter: function () { panel.classList.add("is-active"); }
      });
      gsap.from(panel.querySelectorAll(".fmeta, .fline, .ftime"), {
        opacity: 0, y: 28, duration: 0.9, ease: "expo.out", stagger: 0.08,
        scrollTrigger: { trigger: wrap, start: "top 70%", once: true }
      });

      // incoming card settles flat from a slight deal-the-card tilt
      // (±1.8deg keeps corner overhang inside the card's side margins)
      gsap.fromTo(panel,
        { rotation: i % 2 ? -1.8 : 1.8 },
        {
          rotation: 0, ease: "none",
          scrollTrigger: { trigger: wrap, start: "top bottom", end: "top 25%", scrub: 0.3 }
        });

      // the card underneath recedes as this one slides over it
      if (i > 0) {
        gsap.fromTo(panels[i - 1],
          { scale: 1, opacity: 1, filter: "brightness(1)" },
          {
            scale: 0.93, opacity: 0.45, filter: "brightness(0.6)", ease: "none",
            scrollTrigger: { trigger: wrap, start: "top bottom", end: "top top", scrub: 0.3 }
          });
      }

      // scrub the word fill, left to right, across the sticky hold
      ScrollTrigger.create({
        trigger: wrap, start: "top top", end: "bottom bottom", scrub: 0.3,
        onUpdate: function (self) {
          if (fill) fill.style.clipPath = "inset(-5% " + (100 - self.progress * 100) + "% -5% 0)";
        }
      });
    });
  })();

  /* ════════ TILT CARDS (stats) ════════ */
  (function tilt() {
    if (!finePointer || prefersReduced) return;
    document.querySelectorAll("[data-tilt]").forEach(function (card) {
      card.addEventListener("pointermove", function (e) {
        var r = card.getBoundingClientRect();
        var rx = ((e.clientY - r.top) / r.height - 0.5) * -8;
        var ry = ((e.clientX - r.left) / r.width - 0.5) * 8;
        card.style.transform = "perspective(700px) rotateX(" + rx + "deg) rotateY(" + ry + "deg)";
      });
      card.addEventListener("pointerleave", function () {
        card.style.transform = "perspective(700px) rotateX(0deg) rotateY(0deg)";
      });
    });
  })();

  /* ════════ HERO PARALLAX ON SCROLL ════════ */
  (function heroParallax() {
    if (prefersReduced) return;
    gsap.to(".hero-inner", {
      yPercent: 18, opacity: 0.25, ease: "none",
      scrollTrigger: { trigger: ".hero", start: "top top", end: "bottom top", scrub: 0.5 }
    });
  })();

  /* ════════ TEAM — typographic index + cursor-chasing card ════════
     Desktop: hovering a name dims the rest and a profile card chases
     the cursor, tilting with horizontal velocity. Touch: rows expand
     to show the specialty line.                                       */
  (function team() {
    var list = document.getElementById("teamList");
    if (!list) return;
    var rows = Array.prototype.slice.call(list.querySelectorAll(".tmember"));
    var hoverMQ2 = window.matchMedia("(min-width: 900px) and (hover: hover)");

    // Inject the tap-to-expand specialty rows (mobile) + avatar thumbs.
    // Avatar artwork: data-img on the row, or the generated set by index.
    rows.forEach(function (row, idx) {
      var img = row.getAttribute("data-img") ||
        "images/team/avatar-" + String(idx + 1).padStart(2, "0") + ".webp";
      row.setAttribute("data-img", img);
      var nameEl = row.querySelector(".t-name");
      if (nameEl) {
        var ava = document.createElement("span");
        ava.className = "t-ava";
        ava.style.backgroundImage = "url('" + img + "')";
        nameEl.insertBefore(ava, nameEl.firstChild);
      }

      var d = document.createElement("span");
      d.className = "t-spec-row";
      d.textContent = row.getAttribute("data-spec") || "";
      row.appendChild(d);
      row.addEventListener("click", function () {
        if (hoverMQ2.matches) return; // desktop uses the hover card instead
        var open = row.classList.contains("is-open");
        rows.forEach(function (r) { r.classList.remove("is-open"); });
        if (!open) row.classList.add("is-open");
      });
    });

    if (!prefersReduced) {
      gsap.from(rows, {
        opacity: 0, y: 30, duration: 0.8, ease: "expo.out", stagger: 0.05,
        scrollTrigger: { trigger: list, start: "top 82%", once: true }
      });
    }

    // Cursor-chasing profile card
    var card = document.getElementById("teamCard");
    if (!card || prefersReduced) return;
    var avatar = card.querySelector(".tc-avatar");
    var roleEl = card.querySelector(".tc-role");
    var specEl = card.querySelector(".tc-spec");
    var xTo = gsap.quickTo(card, "x", { duration: 0.45, ease: "power3.out" });
    var yTo = gsap.quickTo(card, "y", { duration: 0.45, ease: "power3.out" });
    var rTo = gsap.quickTo(card, "rotation", { duration: 0.6, ease: "power3.out" });
    var lastX = 0;
    gsap.set(card, { scale: 0.85 });

    list.addEventListener("pointermove", function (e) {
      if (!hoverMQ2.matches || e.pointerType === "touch") return;
      xTo(e.clientX + 24);
      yTo(e.clientY - 112);
      var dx = e.clientX - lastX;
      lastX = e.clientX;
      rTo(Math.max(-10, Math.min(10, dx * 0.6)));
    });

    rows.forEach(function (row) {
      row.addEventListener("pointerenter", function (e) {
        if (!hoverMQ2.matches || e.pointerType === "touch") return;
        // First reveal: snap the card to the cursor instantly, otherwise it
        // visibly flies in from the screen corner (x/y default to 0,0).
        if (!card.classList.contains("is-on")) {
          gsap.set(card, { x: e.clientX + 24, y: e.clientY - 112 });
          lastX = e.clientX;
        }
        var img = row.getAttribute("data-img");
        if (img) {
          avatar.classList.add("has-img");
          avatar.style.backgroundImage = "url('" + img + "')";
          avatar.textContent = "";
        } else {
          var name = row.querySelector(".t-name").textContent.trim();
          avatar.classList.remove("has-img");
          avatar.style.backgroundImage = "";
          avatar.textContent = name.split(/\s+/).map(function (w) { return w.charAt(0); }).slice(0, 2).join("").toUpperCase();
        }
        roleEl.textContent = row.querySelector(".t-role").textContent;
        specEl.textContent = row.getAttribute("data-spec") || "";
        card.classList.add("is-on");
        gsap.to(card, { scale: 1, duration: 0.4, ease: "back.out(1.6)" });
      });
    });

    list.addEventListener("pointerleave", function () {
      card.classList.remove("is-on");
      gsap.to(card, { scale: 0.85, duration: 0.3, ease: "power3.out" });
      rTo(0);
    });
  })();

  /* ════════ FAQ (faq2) — exclusive animated accordion ════════ */
  (function faq2() {
    var items = Array.prototype.slice.call(document.querySelectorAll(".faq2-item"));
    if (!items.length) return;
    items.forEach(function (item) {
      var btn = item.querySelector(".faq2-q");
      btn.addEventListener("click", function () {
        var open = item.classList.contains("is-open");
        items.forEach(function (i) {
          i.classList.remove("is-open");
          i.querySelector(".faq2-q").setAttribute("aria-expanded", "false");
        });
        if (!open) {
          item.classList.add("is-open");
          btn.setAttribute("aria-expanded", "true");
        }
      });
    });
  })();

  /* ════════ TIMELINE — rail fills as you scroll ════════ */
  (function timeline() {
    var tl = document.querySelector(".tline");
    if (!tl) return;
    var fill = tl.querySelector(".tline-fill");
    var steps = Array.prototype.slice.call(tl.querySelectorAll(".tstep"));

    if (prefersReduced) {
      if (fill) fill.style.transform = "scaleY(1)";
      steps.forEach(function (s) { s.classList.add("is-lit"); });
      return;
    }
    ScrollTrigger.create({
      trigger: tl, start: "top 72%", end: "bottom 55%", scrub: 0.4,
      onUpdate: function (self) {
        if (fill) fill.style.transform = "scaleY(" + self.progress + ")";
        steps.forEach(function (s, i) {
          s.classList.toggle("is-lit", self.progress >= i / steps.length + 0.04);
        });
      }
    });
  })();

  /* ════════ BACK TO TOP ════════ */
  (function toTop() {
    var btn = document.getElementById("toTop");
    if (btn) btn.addEventListener("click", function () {
      window.scrollTo({ top: 0, behavior: prefersReduced ? "auto" : "smooth" });
    });
  })();

})();
