const config = {
  publicKey: 'YocnD2Bu57Bcf91rq',
  serviceId: 'service_z8mbo5q',
  contactTemplateId: 'template_2r3we0i'
};

if (window.emailjs) {
  window.emailjs.init({ publicKey: config.publicKey });
}

export default config;
