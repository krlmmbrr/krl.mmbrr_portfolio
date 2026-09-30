import config from './emailjs-config.js';

const form = document.getElementById('contactForm');
const honeypot = document.getElementById('website');
const nameField = document.getElementById('name');
const companyField = document.getElementById('company');
const emailField = document.getElementById('email');
const messageField = document.getElementById('message');
const messageSuggestions = document.getElementById('messageSuggestions');
const suggestionButtons = Array.from(document.querySelectorAll('.message-suggestion'));
const submitButton = document.getElementById('submitBtn');
const submitButtonText = document.getElementById('submitBtnText');
const successMessage = document.getElementById('contactSuccess');
const errorMessage = document.getElementById('contactError');

let isSending = false;
let lastSubmissionAt = 0;
const cooldownMs = 5000;

function showMessage(element, text) {
  element.textContent = text;
  element.classList.remove('hidden');
}

function hideMessages() {
  successMessage.textContent = '';
  errorMessage.textContent = '';
  successMessage.classList.add('hidden');
  errorMessage.classList.add('hidden');
}

function hasObviousRepetition(value) {
  const normalized = value.toLocaleLowerCase().replace(/\s+/gu, ' ').trim();
  return /(.)\1{12,}/u.test(normalized);
}

function isMeaningfulMessage(value) {
  const normalized = value.toLocaleLowerCase().replace(/\s+/gu, ' ').trim();
  return normalized.length >= 12 && !/^(hi|hello|hey|test|yo|sup|ok|okay|help)[!. ]*$/u.test(normalized);
}

function updateMessageSuggestions() {
  if (!messageSuggestions) return;
  messageSuggestions.classList.toggle('hidden', isMeaningfulMessage(messageField.value));
}

function validateForm() {
  const name = nameField.value.trim();
  const company = companyField.value.trim();
  const email = emailField.value.trim();
  const message = messageField.value.trim();

  if (honeypot.value.trim()) return 'Unable to send this message.';
  if (name.length < 2 || name.length > 100 || hasObviousRepetition(name)) return 'Please enter a valid full name.';
  if (company.length > 120 || (company && hasObviousRepetition(company))) return 'Please enter a valid company name.';
  if (email.length > 254 || !/^[^\s@]+@[^\s@]+\.[^\s@]+$/u.test(email)) return 'Please enter a valid email address.';
  if (!message || message.length > 10000 || hasObviousRepetition(message)) return 'Please enter a valid message.';
  if (!isMeaningfulMessage(message)) return 'Please add a little more detail to your message, or choose a suggestion below.';

  return '';
}

function setSending(sending) {
  isSending = sending;
  submitButton.disabled = sending;
  submitButtonText.textContent = sending ? 'Sending Message...' : 'Send Message';
}

form.addEventListener('submit', async (event) => {
  event.preventDefault();
  hideMessages();
  if (isSending) return;

  if (!form.checkValidity()) {
    form.reportValidity();
    return;
  }

  const validationError = validateForm();
  if (validationError) {
    updateMessageSuggestions();
    showMessage(errorMessage, validationError);
    return;
  }

  const now = Date.now();
  if (now - lastSubmissionAt < cooldownMs) {
    showMessage(errorMessage, 'Please wait a moment before sending another message.');
    return;
  }

  const params = {
    from_name: nameField.value.trim(),
    company_name: companyField.value.trim(),
    from_email: emailField.value.trim(),
    message: messageField.value.trim()
  };

  setSending(true);
  try {
    await window.emailjs.send(config.serviceId, config.contactTemplateId, params);
    lastSubmissionAt = Date.now();
    form.reset();
    updateMessageSuggestions();
    showMessage(successMessage, "Message Sent! Thanks for reaching out. Your message has been received, and I'll get back to you as soon as possible.");
  } catch (error) {
    console.error('EmailJS Error:', error);
    const actualError = error?.text || error?.message || String(error || '').trim() || 'EmailJS request failed.';
    showMessage(errorMessage, actualError);
  } finally {
    setSending(false);
  }
});

messageField.addEventListener('input', updateMessageSuggestions);
suggestionButtons.forEach((button) => {
  button.addEventListener('click', () => {
    messageField.value = button.dataset.suggestion || '';
    messageField.focus();
    updateMessageSuggestions();
  });
});
