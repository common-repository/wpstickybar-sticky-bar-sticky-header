import{j as e,a,u as r,c as n,X as d}from"./index.js";import{V as i,S as c,v as m}from"./Views.js";const p=()=>{const s=a(),l=r(t=>t.theme.themeColor),o=r(t=>t.theme.primaryColorLevel);return e.jsx("div",{className:n("fixed ltr:right-0 rtl:left-0 top-96 p-3 ltr:rounded-tl-md ltr:rounded-bl-md rtl:rounded-tr-md rtl:rounded-br-md text-white text-xl cursor-pointer select-none",`bg-${l}-${o}`),onClick:()=>{s(d(!0))},children:e.jsx(m,{})})},h=()=>e.jsxs("div",{className:"app-layout-blank flex flex-auto flex-col h-[100vh]",children:[e.jsx(i,{}),e.jsx(p,{}),e.jsx(c,{className:"hidden"})]});export{h as default};