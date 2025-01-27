import { defineComponent as F, ref as f, computed as K, watch as T, onMounted as M, openBlock as d, createElementBlock as v, withDirectives as $, createElementVNode as B, normalizeClass as R, vModelDynamic as N, createCommentVNode as j, Fragment as W, renderList as H, createBlock as S } from "vue";
const V = { style: { display: "flex", "align-items": "center" } }, q = ["type", "inputmode", "placeholder", "disabled"], z = { key: 0 }, G = ["innerHTML"], U = /* @__PURE__ */ F({
  __name: "single-otp-input",
  props: {
    inputType: { default: "tel" },
    inputmode: { default: "numeric" },
    value: {},
    separator: { default: "" },
    focus: { type: Boolean, default: !1 },
    inputClasses: { default: "" },
    conditionalClass: { default: "" },
    shouldAutoFocus: { type: Boolean, default: !1 },
    isLastChild: { type: Boolean, default: !1 },
    placeholder: { default: "" },
    isDisabled: { type: Boolean, default: !1 }
  },
  emits: ["on-change", "on-keydown", "on-paste", "on-focus", "on-blur"],
  setup(b, { emit: I }) {
    const i = b, u = I, l = f(i.value || ""), a = f(null), t = () => (l.value = l.value, l.value.length > 1 && (l.value = l.value.slice(0, 1)), u("on-change", l.value.toString())), h = (n) => n >= 65 && n <= 90, D = (n) => n >= 48 && n <= 57 || n >= 96 && n <= 105, k = (n) => {
      i.isDisabled && n.preventDefault();
      const s = n || window.event, c = s.which ? s.which : s.keyCode;
      D(c) || i.inputType === "letter-numeric" && h(c) || [8, 9, 13, 37, 39, 46, 86].includes(c) ? u("on-keydown", n) : s.preventDefault();
    }, m = (n) => u("on-paste", n), r = () => (a.value.select(), u("on-focus")), y = () => u("on-blur"), g = K(
      () => ["letter-numeric", "number"].includes(i.inputType) ? "text" : i.inputType
    );
    return T(
      () => i.value,
      (n, s) => {
        n !== s && (l.value = n);
      }
    ), T(
      () => i.focus,
      (n, s) => {
        s !== n && a.value && i.focus && (a.value.focus(), a.value.select());
      }
    ), M(() => {
      a.value && i.focus && i.shouldAutoFocus && (a.value.focus(), a.value.select());
    }), (n, s) => (d(), v("div", V, [
      $(B("input", {
        "data-test": "single-input",
        type: g.value,
        inputmode: n.inputmode,
        placeholder: n.placeholder,
        disabled: n.isDisabled,
        ref_key: "input",
        ref: a,
        min: "0",
        max: "9",
        maxlength: 1,
        pattern: "[0-9]",
        "onUpdate:modelValue": s[0] || (s[0] = (c) => l.value = c),
        class: R([n.inputClasses, n.conditionalClass, { "is-complete": l.value }]),
        onInput: t,
        onKeydown: k,
        onPaste: m,
        onFocus: r,
        onBlur: y
      }, null, 42, q), [
        [N, l.value]
      ]),
      !n.isLastChild && n.separator ? (d(), v("span", z, [
        B("span", { innerHTML: n.separator }, null, 8, G)
      ])) : j("", !0)
    ]));
  }
}), J = {
  style: { display: "flex" },
  class: "otp-input-container"
}, Q = {
  key: 0,
  autocomplete: "off",
  name: "hidden",
  type: "text",
  style: { display: "none" }
}, X = 8, Y = 37, Z = 39, x = 46, ne = /* @__PURE__ */ F({
  __name: "vue3-otp-input",
  props: {
    value: { default: "" },
    numInputs: { default: 4 },
    separator: { default: "" },
    inputClasses: { default: "" },
    conditionalClass: {},
    inputType: {},
    inputmode: { default: "text" },
    shouldAutoFocus: { type: Boolean, default: !1 },
    placeholder: {},
    isDisabled: { type: Boolean },
    shouldFocusOrder: { type: Boolean }
  },
  emits: ["update:value", "on-change", "on-complete"],
  setup(b, { expose: I, emit: i }) {
    const u = b, l = i, a = f(0), t = f([]), h = f([]);
    T(
      () => u.value,
      (e) => {
        if (e.length === u.numInputs || t.value.length === 0) {
          const o = e.split("");
          t.value = o;
        }
      },
      { immediate: !0 }
    );
    const D = (e) => {
      a.value = e;
    }, k = () => {
      a.value = -1;
    }, m = () => t.value.join("").length === u.numInputs ? (l("update:value", t.value.join("")), l("on-complete", t.value.join(""))) : "Wait until the user enters the required number of characters", r = (e) => {
      a.value = Math.max(Math.min(u.numInputs - 1, e), 0);
    }, y = () => {
      r(a.value + 1);
    }, g = () => {
      r(a.value - 1);
    }, n = (e) => {
      h.value = Object.assign([], t.value), t.value[a.value] = e.toString(), h.value.join("") !== t.value.join("") && (l("update:value", t.value.join("")), l("on-change", t.value.join("")), m());
    }, s = (e) => {
      e.preventDefault();
      const o = e.clipboardData.getData("text/plain").slice(0, u.numInputs - a.value).split("");
      if (u.inputType === "number" && !o.join("").match(/^\d+$/) || u.inputType === "letter-numeric" && !o.join("").match(/^\w+$/))
        return "Invalid pasted data";
      const p = t.value.slice(0, a.value).concat(o);
      return p.slice(0, u.numInputs).forEach(function(O, C) {
        t.value[C] = O;
      }), r(p.slice(0, u.numInputs).length), m();
    }, c = (e) => {
      n(e), y();
    }, A = () => {
      t.value.length > 0 && (l("update:value", ""), l("on-change", "")), t.value = [], a.value = 0;
    }, E = (e) => {
      const o = e.split("");
      o.length === u.numInputs && (t.value = o, l("update:value", t.value.join("")), l("on-complete", t.value.join("")));
    }, L = (e, o) => {
      switch (e.keyCode) {
        case X:
          e.preventDefault(), n(""), g();
          break;
        case x:
          e.preventDefault(), n("");
          break;
        case Y:
          e.preventDefault(), g();
          break;
        case Z:
          e.preventDefault(), y();
          break;
        default:
          P(o);
          break;
      }
    }, P = (e) => {
      u.shouldFocusOrder && setTimeout(() => {
        const o = t.value.join("").length;
        e - o >= 0 && (a.value = o, t.value[e] = "");
      }, 100);
    };
    return I({
      clearInput: A,
      fillInput: E
    }), (e, o) => (d(), v("div", J, [
      e.inputType === "password" ? (d(), v("input", Q)) : j("", !0),
      (d(!0), v(W, null, H(e.numInputs, (_, p) => {
        var O, C;
        return d(), S(U, {
          key: p,
          focus: a.value === p,
          value: t.value[p],
          separator: e.separator,
          "input-type": e.inputType,
          inputmode: e.inputmode,
          "input-classes": e.inputClasses,
          conditionalClass: (O = e.conditionalClass) == null ? void 0 : O[p],
          "is-last-child": p === e.numInputs - 1,
          "should-auto-focus": e.shouldAutoFocus,
          placeholder: (C = e.placeholder) == null ? void 0 : C[p],
          "is-disabled": e.isDisabled,
          onOnChange: c,
          onOnKeydown: (w) => L(w, p),
          onOnPaste: s,
          onOnFocus: (w) => D(p),
          onOnBlur: k
        }, null, 8, ["focus", "value", "separator", "input-type", "inputmode", "input-classes", "conditionalClass", "is-last-child", "should-auto-focus", "placeholder", "is-disabled", "onOnKeydown", "onOnFocus"]);
      }), 128))
    ]));
  }
});
export {
  ne as default
};
