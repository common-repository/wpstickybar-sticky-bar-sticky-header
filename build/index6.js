import{r as C,aq as F,j as s,ar as y}from"./index.js";import{c as N,b as c,q as R,e as S,f as k,h as I,F as l,a as m}from"./StatusIcon.js";import{B as p}from"./Button.js";import{u as M,A as $,a as q}from"./useTimeOutMessage.js";import{P as w}from"./PasswordInput.js";import"./isNil.js";import"./Views.js";const A=N().shape({password:c().required("Please enter your password"),confirmPassword:c().oneOf([R("password")],"Your passwords do not match")}),B=u=>{const{disableSubmit:f=!1,className:h,signInUrl:x="/sign-in"}=u,[n,j]=C.useState(!1),[t,P]=M(),b=F(),g=async(a,e)=>{var i,d;const{password:o}=a;e(!0);try{(await y({password:o})).data&&(e(!1),j(!0))}catch(r){P(((d=(i=r==null?void 0:r.response)==null?void 0:i.data)==null?void 0:d.message)||r.toString()),e(!1)}},v=()=>{b("/sign-in")};return s.jsxs("div",{className:h,children:[s.jsx("div",{className:"mb-6",children:n?s.jsxs(s.Fragment,{children:[s.jsx("h3",{className:"mb-1",children:"Reset done"}),s.jsx("p",{children:"Your password has been successfully reset"})]}):s.jsxs(s.Fragment,{children:[s.jsx("h3",{className:"mb-1",children:"Set new password"}),s.jsx("p",{children:"Your new password must different to previos password"})]})}),t&&s.jsx($,{showIcon:!0,className:"mb-4",type:"danger",children:t}),s.jsx(S,{initialValues:{password:"123Qwe1",confirmPassword:"123Qwe1"},validationSchema:A,onSubmit:(a,{setSubmitting:e})=>{f?e(!1):g(a,e)},children:({touched:a,errors:e,isSubmitting:o})=>s.jsx(k,{children:s.jsxs(I,{children:[n?s.jsx(p,{block:!0,variant:"solid",type:"button",onClick:v,children:"Continue"}):s.jsxs(s.Fragment,{children:[s.jsx(l,{label:"Password",invalid:e.password&&a.password,errorMessage:e.password,children:s.jsx(m,{autoComplete:"off",name:"password",placeholder:"Password",component:w})}),s.jsx(l,{label:"Confirm Password",invalid:e.confirmPassword&&a.confirmPassword,errorMessage:e.confirmPassword,children:s.jsx(m,{autoComplete:"off",name:"confirmPassword",placeholder:"Confirm Password",component:w})}),s.jsx(p,{block:!0,loading:o,variant:"solid",type:"submit",children:o?"Submiting...":"Submit"})]}),s.jsxs("div",{className:"mt-4 text-center",children:[s.jsx("span",{children:"Back to "}),s.jsx(q,{to:x,children:"Sign in"})]})]})})})]})},Y=()=>s.jsx(B,{disableSubmit:!1}),z=Y;export{z as default};