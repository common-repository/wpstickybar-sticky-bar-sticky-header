import{u as c,j as e,g as M,c as x,N as E}from"./index.js";import{L as T,H as k}from"./Logo.js";import{V as N,S as L}from"./Views.js";import{g as p,D as j,n as H,h as I,i as _,a as w,u as C,M as A,U as D}from"./UserDropdown.js";import{u,n as F,A as d}from"./navigation-icon.config.js";import"./isNil.js";const P=()=>{const r=c(t=>t.theme.mode);return e.jsx(T,{mode:r,className:"hidden md:block"})},g=({path:r,children:t,className:a})=>e.jsx(M,{className:x("h-full w-full flex items-center",a),to:r,children:e.jsx("span",{children:t})}),f=({nav:r,isLink:t,manuVariant:a})=>{const{title:s,translateKey:n,icon:o,path:l}=r,{t:h}=u(),m=h(n,s),i=o&&e.jsx("span",{className:"text-2xl",children:F[o]});return e.jsx(e.Fragment,{children:l&&t?e.jsx(g,{path:l,children:e.jsx(p,{variant:a,children:e.jsxs("span",{className:"flex items-center gap-2",children:[i,m]})})}):e.jsxs(p,{variant:a,children:[i,e.jsx("span",{children:m})]})})},y=({nav:r})=>{const{title:t,translateKey:a,path:s,key:n}=r,{t:o}=u(),l=o(a,t);return e.jsx(j.Item,{eventKey:n,className:x(s&&"px-0"),children:s?e.jsx(g,{path:s,className:x(s&&"px-2"),children:l}):e.jsx("span",{children:l})})},V=({manuVariant:r,userAuthority:t=[]})=>{const{t:a}=u();return e.jsx("span",{className:"flex items-center",children:H.map(s=>s.type===I||s.type===_?e.jsx(d,{authority:s.authority,userAuthority:t,children:e.jsx(j,{trigger:"hover",renderTitle:e.jsx(f,{manuVariant:r,nav:s}),children:s.subMenu.map(n=>e.jsx(d,{authority:n.authority,userAuthority:t,children:n.subMenu.length>0?e.jsx(j.Menu,{title:a(n.translateKey,n.title),children:n.subMenu.map(o=>e.jsx(d,{authority:o.authority,userAuthority:t,children:e.jsx(y,{nav:o})},o.key))}):e.jsx(y,{nav:n},n.key)},n.key))})},s.key):s.type===w?e.jsx(d,{authority:s.authority,userAuthority:t,children:e.jsx(f,{isLink:!0,nav:s,manuVariant:r})},s.key):e.jsx(e.Fragment,{}))})},v=r=>{const{className:t,contained:a}=r,s=c(i=>i.theme.navMode),n=c(i=>i.theme.themeColor),o=c(i=>i.theme.primaryColorLevel),l=c(i=>i.auth.user.authority),{larger:h}=C(),m=()=>s===E?`bg-${n}-${o} secondary-header-${s}`:`secondary-header-${s}`;return e.jsx(e.Fragment,{children:h.md&&e.jsx("div",{className:x("h-16 flex items-center",m(),t),children:e.jsx("div",{className:x("flex items-center px-4",a&&"container mx-auto"),children:e.jsx(V,{manuVariant:s,userAuthority:l})})})})},z=()=>e.jsxs(e.Fragment,{children:[e.jsx(P,{}),e.jsx(A,{})]}),K=()=>e.jsxs(e.Fragment,{children:[e.jsx(L,{}),e.jsx(D,{hoverable:!1})]}),B=()=>e.jsx("div",{className:"app-layout-simple flex flex-auto flex-col min-h-screen",children:e.jsx("div",{className:"flex flex-auto min-w-0",children:e.jsxs("div",{className:"flex flex-col flex-auto min-h-screen min-w-0 relative w-full",children:[e.jsx(k,{container:!0,className:"shadow dark:shadow-2xl",headerStart:e.jsx(z,{}),headerEnd:e.jsx(K,{})}),e.jsx(v,{contained:!0}),e.jsx(N,{pageContainerType:"contained"})]})})});export{B as default};