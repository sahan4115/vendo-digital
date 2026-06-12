/* ════════════════════════════════════════════════════════════
   VENDO CHAT — "Venny", the enquiry assistant.

   Dual mode:
   • AI mode      — when window.VENDO_CHAT_CFG.ai is true (WordPress
                    plugin with an Anthropic API key configured), free
                    text is answered by Claude via the REST proxy.
   • Guided mode  — no backend needed: intent matching + scripted
                    flows. Used on the static site and as the fallback
                    whenever the AI endpoint fails.

   Enquiries POST to the WordPress REST endpoint when available,
   otherwise compose a mailto: draft.
   ════════════════════════════════════════════════════════════ */
(function () {
  "use strict";

  var CFG = window.VENDO_CHAT_CFG || null; // injected by the WP plugin
  var EMAIL = (CFG && CFG.email) || "hello@vendodigital.co.uk";
  var PHONE = (CFG && CFG.phone) || "0207 101 4967";

  /* ── State ─────────────────────────────────── */
  var open = false;
  var busy = false;
  var history = [];          // [{role:'user'|'assistant', content:'...'}]
  var lead = null;           // active lead-capture flow state
  var greeted = false;

  /* ── DOM scaffold ──────────────────────────── */
  var root = document.createElement("div");
  root.id = "vchat";
  root.innerHTML =
    '<button id="vchatBtn" aria-label="Chat with Vendo" aria-expanded="false">' +
      '<svg viewBox="0 0 397 384" aria-hidden="true"><path d="M359.62,20.46l-121.37,342.18h-104.31L12.58,20.46h88.72l84.82,258.35L271.42,20.46h88.2Z"/><circle cx="359.5" cy="339" r="24.5"/></svg>' +
      '<span class="vchat-badge" aria-hidden="true"></span>' +
    "</button>" +
    '<div id="vchatPanel" role="dialog" aria-label="Vendo chat" aria-hidden="true">' +
      '<div class="vchat-head">' +
        '<div class="vchat-id"><span class="vchat-avatar">V.</span><div><strong>Venny</strong><em>Vendo’s assistant · online</em></div></div>' +
        '<button class="vchat-close" aria-label="Close chat">×</button>' +
      "</div>" +
      '<div class="vchat-log" aria-live="polite"></div>' +
      '<div class="vchat-chips"></div>' +
      '<form class="vchat-bar">' +
        '<input type="text" placeholder="Type a message…" aria-label="Message" maxlength="600" />' +
        '<button type="submit" aria-label="Send"><svg viewBox="0 0 24 24" fill="none"><path d="M4 12h14M13 6l6 6-6 6" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg></button>' +
      "</form>" +
    "</div>";
  document.body.appendChild(root);

  var btn = root.querySelector("#vchatBtn");
  var panel = root.querySelector("#vchatPanel");
  var log = root.querySelector(".vchat-log");
  var chipsEl = root.querySelector(".vchat-chips");
  var form = root.querySelector(".vchat-bar");
  var input = form.querySelector("input");

  /* ── UI helpers ────────────────────────────── */
  function scrollDown() { log.scrollTop = log.scrollHeight; }

  function addMsg(role, text) {
    var el = document.createElement("div");
    el.className = "vchat-msg " + (role === "user" ? "me" : "bot");
    el.textContent = text;
    log.appendChild(el);
    scrollDown();
    if (role === "user" || role === "assistant") {
      history.push({ role: role === "user" ? "user" : "assistant", content: text });
      if (history.length > 16) history.splice(0, history.length - 16);
    }
  }

  function typing(on) {
    var t = log.querySelector(".vchat-typing");
    if (on && !t) {
      t = document.createElement("div");
      t.className = "vchat-msg bot vchat-typing";
      t.innerHTML = "<i></i><i></i><i></i>";
      log.appendChild(t);
      scrollDown();
    } else if (!on && t) {
      t.remove();
    }
  }

  function setChips(list) {
    chipsEl.innerHTML = "";
    (list || []).forEach(function (c) {
      var b = document.createElement("button");
      b.type = "button";
      b.textContent = c.label;
      b.addEventListener("click", function () { c.action(c.label); });
      chipsEl.appendChild(b);
    });
  }

  function botSay(text, chips, delay) {
    typing(true);
    setTimeout(function () {
      typing(false);
      addMsg("assistant", text);
      setChips(chips || defaultChips());
    }, delay == null ? 500 : delay);
  }

  /* ── Lead capture flow ─────────────────────── */
  var LEAD_STEPS = [
    { key: "name",  ask: "Great — I’ll take a few details and the team will be in touch. What’s your name?", validate: function (v) { return v.length >= 2; }, err: "Could you give me your name?" },
    { key: "email", ask: null, validate: function (v) { return /^[^\s@]+@[^\s@]+\.[^\s@]{2,}$/.test(v); }, err: "That doesn’t look like an email — could you check it?" },
    { key: "phone", ask: null, optional: true, validate: function (v) { return v.replace(/\D/g, "").length >= 7; }, err: "That doesn’t look like a phone number — try again, or tap Skip." },
    { key: "note",  ask: null, validate: function (v) { return v.length >= 2; }, err: "Tell me a little about what you need." }
  ];

  function leadAskText(step) {
    switch (step.key) {
      case "email": return "Thanks " + lead.data.name.split(" ")[0] + "! What’s the best email for you?";
      case "phone": return "And a phone number? (optional — tap Skip if you’d rather not)";
      case "note":  return "Last one: what do you need help with? A sentence is plenty.";
      default:      return step.ask;
    }
  }

  function startLead(topic) {
    lead = { step: 0, data: { topic: topic || "General enquiry" } };
    botSay(LEAD_STEPS[0].ask, []);
  }

  function leadChips(step) {
    return step.optional
      ? [{ label: "Skip", action: function () { leadNext(""); } }]
      : [];
  }

  function leadNext(value) {
    var step = LEAD_STEPS[lead.step];
    if (value !== "" || !step.optional) {
      if (!step.validate(value)) { botSay(step.err, leadChips(step)); return; }
      lead.data[step.key] = value;
    } else {
      lead.data[step.key] = "";
    }
    lead.step++;
    if (lead.step < LEAD_STEPS.length) {
      var next = LEAD_STEPS[lead.step];
      botSay(leadAskText(next), leadChips(next));
    } else {
      submitLead();
    }
  }

  function submitLead() {
    var d = lead.data;
    lead = null;
    typing(true);

    if (CFG && CFG.restUrl) {
      fetch(CFG.restUrl + "enquiry", {
        method: "POST",
        headers: { "Content-Type": "application/json" },
        body: JSON.stringify({
          name: d.name, email: d.email, phone: d.phone || "",
          topic: d.topic, message: d.note,
          website: "" // honeypot — humans never fill this
        })
      })
        .then(function (r) { return r.json(); })
        .then(function (res) {
          typing(false);
          if (res && res.ok) {
            addMsg("assistant", "Done! ✅ The team has your details and will reply within one working day — usually faster. Anything else I can help with?");
          } else { throw new Error("save failed"); }
          setChips(defaultChips());
        })
        .catch(function () {
          typing(false);
          mailtoFallback(d);
        });
    } else {
      typing(false);
      mailtoFallback(d);
    }
  }

  function mailtoFallback(d) {
    var body = "Name: " + d.name + "\nEmail: " + d.email +
      (d.phone ? "\nPhone: " + d.phone : "") +
      "\nTopic: " + d.topic + "\n\n" + d.note;
    var href = "mailto:" + EMAIL + "?subject=" +
      encodeURIComponent("Website enquiry — " + d.topic) +
      "&body=" + encodeURIComponent(body);
    addMsg("assistant", "I’ve drafted your enquiry — your email app should open now. If it doesn’t, email us at " + EMAIL + " or call " + PHONE + ".");
    setChips(defaultChips());
    window.location.href = href;
  }

  /* ── Guided brain (no API needed) ──────────── */
  var INTENTS = [
    { re: /(price|cost|fee|charge|budget|how much|expensive)/i,
      reply: "Fair question! Pricing depends on scope, so we don’t do one-size-fits-all numbers. The free audit is genuinely free though — it shows what’s leaking and what we’d do, then you decide. Want one?",
      chips: function () { return [auditChip(), humanChip()]; } },
    { re: /(dental|dentist|practice|surgery|implant|invisalign|patient)/i,
      reply: "Dental is our specialty \u{1F9B7} — we took one practice from a standing start to £90K/month in 12 months, and we’re at the Scottish Dental Show most years. From squat practices to multi-surgery groups, we fill appointment books.",
      chips: function () { return [auditChip("Dental marketing"), humanChip()]; } },
    { re: /(shop|store|e-?com|shopify|woocommerce|product|roas|order)/i,
      reply: "We grow online stores with Google Shopping, paid social and Shopify builds — measured on ROAS and order value, not vanity clicks.",
      chips: function () { return [auditChip("E-commerce"), humanChip()]; } },
    { re: /(seo|rank|google search|organic|keyword)/i,
      reply: "Our SEO is technical fixes + content + digital PR that compound month over month — and we report it as revenue and leads, not just rankings.",
      chips: function () { return [auditChip("SEO"), humanChip()]; } },
    { re: /(ads|ppc|adwords|google ads|paid|campaign)/i,
      reply: "Google Ads here is run by a Head of Paid Media who used to work at Google. Campaigns aimed at buyers, weekly optimisation, no minimum term.",
      chips: function () { return [auditChip("Google Ads"), humanChip()]; } },
    { re: /(web ?site|web ?design|wordpress|landing page|redesign)/i,
      reply: "We build fast, conversion-focused sites on WordPress and Shopify — designed to turn visitors into enquiries, not just look pretty.",
      chips: function () { return [auditChip("Web design"), humanChip()]; } },
    { re: /(audit|review my|look at my site)/i,
      reply: "The free audit is human-written and covers your site, ads and rankings — what’s leaking and what it’s costing you. Yours in 48 hours.",
      chips: function () { return [auditChip(), humanChip()]; } },
    { re: /(human|person|talk|call|phone|speak)/i,
      reply: "Of course — you can call " + PHONE + " or email " + EMAIL + ". Or leave your details here and the team will ring you.",
      chips: function () { return [auditChip("Callback request"), { label: "No thanks", action: function (l) { addMsg("user", l); botSay("No problem! I’m here if you need me."); } }]; } },
    { re: /(where|address|location|based|office)/i,
      reply: "We’re at 5 Sandiford Road, Sutton, Surrey — and we work with businesses across the UK.",
      chips: function () { return [auditChip(), humanChip()]; } },
    { re: /(hello|^hi\b|hey|morning|afternoon)/i,
      reply: "Hi there! \u{1F44B} What brings you to Vendo today?",
      chips: null }
  ];

  function auditChip(topic) {
    return { label: "Get my free audit", action: function (l) { addMsg("user", l); startLead(topic || "Free audit"); } };
  }
  function humanChip() {
    return { label: "Talk to a human", action: function (l) { addMsg("user", l); startLead("Callback request"); } };
  }

  function defaultChips() {
    return [
      { label: "I need more customers", action: function (l) { addMsg("user", l); botSay("That’s what we do \u{1F4C8} — PPC, SEO and websites that pay for themselves. The best first step is a free audit of your site, ads and rankings: human-written, yours in 48 hours.", [auditChip(), humanChip()]); } },
      { label: "Dental marketing", action: function (l) { addMsg("user", l); routeGuided("dental"); } },
      { label: "E-commerce", action: function (l) { addMsg("user", l); routeGuided("ecommerce shop"); } },
      auditChip()
    ];
  }

  function routeGuided(text) {
    for (var i = 0; i < INTENTS.length; i++) {
      if (INTENTS[i].re.test(text)) {
        botSay(INTENTS[i].reply, INTENTS[i].chips ? INTENTS[i].chips() : defaultChips());
        return;
      }
    }
    botSay("Good question — that one’s best answered by the team. Leave your details and they’ll come back to you within one working day.", [auditChip("Question: " + text.slice(0, 80)), humanChip()]);
  }

  /* ── AI mode (WordPress proxy) ─────────────── */
  function routeAI(text) {
    busy = true;
    typing(true);
    fetch(CFG.restUrl + "message", {
      method: "POST",
      headers: { "Content-Type": "application/json" },
      body: JSON.stringify({ messages: history })
    })
      .then(function (r) { return r.json(); })
      .then(function (res) {
        busy = false;
        typing(false);
        if (res && res.reply) {
          addMsg("assistant", res.reply);
          setChips([auditChip(), humanChip()]);
        } else {
          routeGuided(text); // graceful fallback
        }
      })
      .catch(function () {
        busy = false;
        typing(false);
        routeGuided(text);
      });
  }

  /* ── Wiring ────────────────────────────────── */
  function setOpen(o) {
    open = o;
    panel.setAttribute("aria-hidden", String(!o));
    btn.setAttribute("aria-expanded", String(o));
    root.classList.toggle("is-open", o);
    if (o && !greeted) {
      greeted = true;
      botSay("Hi! I’m Venny \u{1F44B} — I can answer questions about Vendo, or sort you out with a free site audit. What brings you here?", defaultChips(), 350);
    }
    if (o) setTimeout(function () { input.focus(); }, 350);
  }

  btn.addEventListener("click", function () { setOpen(!open); });
  root.querySelector(".vchat-close").addEventListener("click", function () { setOpen(false); });
  document.addEventListener("keydown", function (e) { if (e.key === "Escape" && open) setOpen(false); });

  form.addEventListener("submit", function (e) {
    e.preventDefault();
    var text = input.value.trim();
    if (!text || busy) return;
    input.value = "";
    addMsg("user", text);
    setChips([]);
    if (lead) { leadNext(text); return; }
    if (CFG && CFG.ai) { routeAI(text); } else { routeGuided(text); }
  });
})();
