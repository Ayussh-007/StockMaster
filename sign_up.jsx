import { useState, useEffect } from 'react';
import { Package, Eye, EyeOff, Check, X, ArrowRight, Boxes, BarChart3, Truck, Warehouse } from 'lucide-react';

export default function SignupPage() {
  const [showPassword, setShowPassword] = useState(false);
  const [formData, setFormData] = useState({
    loginId: '',
    email: '',
    company: '',
    password: ''
  });
  const [agreed, setAgreed] = useState(false);
  const [errors, setErrors] = useState({});
  const [touched, setTouched] = useState({});

  // Simulated database of existing users
  const existingUsers = ['admin123', 'user2024', 'manager1'];
  const existingEmails = ['admin@company.com', 'user@test.com'];

  const validateLoginId = (id) => {
    if (id.length < 6 || id.length > 12) {
      return 'Login ID must be 6-12 characters';
    }
    if (existingUsers.includes(id.toLowerCase())) {
      return 'Login ID already exists';
    }
    return '';
  };

  const validateEmail = (email) => {
    const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    if (!emailRegex.test(email)) {
      return 'Invalid email format';
    }
    if (existingEmails.includes(email.toLowerCase())) {
      return 'Email already registered';
    }
    return '';
  };

  const validatePassword = (pwd) => {
    const checks = {
      length: pwd.length >= 8,
      lowercase: /[a-z]/.test(pwd),
      uppercase: /[A-Z]/.test(pwd),
      special: /[!@#$%^&*(),.?":{}|<>]/.test(pwd)
    };
    return checks;
  };

  const passwordChecks = validatePassword(formData.password);
  const isPasswordValid = Object.values(passwordChecks).every(Boolean);

  useEffect(() => {
    const newErrors = {};
    if (touched.loginId && formData.loginId) {
      newErrors.loginId = validateLoginId(formData.loginId);
    }
    if (touched.email && formData.email) {
      newErrors.email = validateEmail(formData.email);
    }
    setErrors(newErrors);
  }, [formData, touched]);

  const handleChange = (e) => {
    setFormData({ ...formData, [e.target.name]: e.target.value });
  };

  const handleBlur = (field) => {
    setTouched({ ...touched, [field]: true });
  };

  const isFormValid = () => {
    return (
      formData.loginId &&
      !validateLoginId(formData.loginId) &&
      formData.email &&
      !validateEmail(formData.email) &&
      formData.company &&
      isPasswordValid &&
      agreed
    );
  };

  const handleSubmit = () => {
    if (isFormValid()) {
      alert('Account created successfully!');
    }
  };

  const PasswordCheck = ({ valid, label }) => (
    <div className="flex items-center gap-2 text-sm">
      {valid ? (
        <Check className="w-4 h-4 text-emerald-500" />
      ) : (
        <X className="w-4 h-4 text-slate-300" />
      )}
      <span className={valid ? 'text-emerald-600' : 'text-slate-400'}>{label}</span>
    </div>
  );

  return (
    <div className="min-h-screen bg-gradient-to-br from-amber-50 via-orange-50 to-yellow-50">
      {/* Header with Company Branding */}
      <div className="bg-gradient-to-r from-amber-600 via-orange-500 to-amber-600 py-6 px-8 shadow-lg">
        <div className="max-w-6xl mx-auto flex items-center justify-between">
          <div className="flex items-center gap-4">
            <div className="w-14 h-14 bg-white/20 rounded-2xl flex items-center justify-center backdrop-blur-sm border border-white/30 shadow-lg">
              <Boxes className="w-8 h-8 text-white" />
            </div>
            <div>
              <h1 className="text-2xl font-bold text-white tracking-tight">InventoryPro</h1>
              <p className="text-amber-100 text-sm">Smart Stock Management Solutions</p>
            </div>
          </div>
          <div className="hidden md:flex items-center gap-6 text-white/80">
            <div className="flex items-center gap-2">
              <Warehouse className="w-5 h-5" />
              <span className="text-sm">Multi-Warehouse</span>
            </div>
            <div className="flex items-center gap-2">
              <BarChart3 className="w-5 h-5" />
              <span className="text-sm">Analytics</span>
            </div>
            <div className="flex items-center gap-2">
              <Truck className="w-5 h-5" />
              <span className="text-sm">Logistics</span>
            </div>
          </div>
        </div>
      </div>

      {/* Main Content */}
      <div className="flex items-center justify-center py-10 px-4">
        <div className="w-full max-w-lg">
          <div className="bg-white rounded-3xl shadow-xl shadow-orange-200/50 p-8 border border-orange-100">
            <div className="text-center mb-8">
              <div className="w-16 h-16 bg-gradient-to-br from-amber-500 to-orange-600 rounded-2xl flex items-center justify-center mx-auto mb-4 shadow-lg shadow-orange-300/50">
                <Package className="w-9 h-9 text-white" />
              </div>
              <h2 className="text-2xl font-bold text-slate-800 mb-2">Create Your Account</h2>
              <p className="text-slate-500">Start managing your inventory efficiently</p>
            </div>

            <div className="space-y-5">
              {/* Login ID */}
              <div>
                <label className="block text-sm font-medium text-slate-700 mb-2">
                  Login ID <span className="text-orange-500">*</span>
                </label>
                <input
                  type="text"
                  name="loginId"
                  value={formData.loginId}
                  onChange={handleChange}
                  onBlur={() => handleBlur('loginId')}
                  placeholder="Enter 6-12 characters"
                  maxLength={12}
                  className={`w-full px-4 py-3 rounded-xl border ${
                    errors.loginId ? 'border-red-300 bg-red-50' : 
                    touched.loginId && formData.loginId && !errors.loginId ? 'border-emerald-300 bg-emerald-50' :
                    'border-slate-200 bg-white'
                  } focus:outline-none focus:ring-2 focus:ring-amber-500 focus:border-transparent transition-all text-slate-800 placeholder-slate-400`}
                />
                <div className="flex justify-between mt-1">
                  {errors.loginId ? (
                    <span className="text-red-500 text-sm">{errors.loginId}</span>
                  ) : (
                    <span className="text-slate-400 text-sm">Must be unique</span>
                  )}
                  <span className="text-slate-400 text-sm">{formData.loginId.length}/12</span>
                </div>
              </div>

              {/* Email */}
              <div>
                <label className="block text-sm font-medium text-slate-700 mb-2">
                  Email Address <span className="text-orange-500">*</span>
                </label>
                <input
                  type="email"
                  name="email"
                  value={formData.email}
                  onChange={handleChange}
                  onBlur={() => handleBlur('email')}
                  placeholder="your@email.com"
                  className={`w-full px-4 py-3 rounded-xl border ${
                    errors.email ? 'border-red-300 bg-red-50' : 
                    touched.email && formData.email && !errors.email ? 'border-emerald-300 bg-emerald-50' :
                    'border-slate-200 bg-white'
                  } focus:outline-none focus:ring-2 focus:ring-amber-500 focus:border-transparent transition-all text-slate-800 placeholder-slate-400`}
                />
                {errors.email && <span className="text-red-500 text-sm mt-1 block">{errors.email}</span>}
              </div>

              {/* Company Name */}
              <div>
                <label className="block text-sm font-medium text-slate-700 mb-2">
                  Company Name <span className="text-orange-500">*</span>
                </label>
                <input
                  type="text"
                  name="company"
                  value={formData.company}
                  onChange={handleChange}
                  placeholder="Your Company Inc."
                  className="w-full px-4 py-3 rounded-xl border border-slate-200 bg-white focus:outline-none focus:ring-2 focus:ring-amber-500 focus:border-transparent transition-all text-slate-800 placeholder-slate-400"
                />
              </div>

              {/* Password */}
              <div>
                <label className="block text-sm font-medium text-slate-700 mb-2">
                  Password <span className="text-orange-500">*</span>
                </label>
                <div className="relative">
                  <input
                    type={showPassword ? 'text' : 'password'}
                    name="password"
                    value={formData.password}
                    onChange={handleChange}
                    placeholder="Create a strong password"
                    className={`w-full px-4 py-3 rounded-xl border ${
                      formData.password && isPasswordValid ? 'border-emerald-300 bg-emerald-50' :
                      'border-slate-200 bg-white'
                    } focus:outline-none focus:ring-2 focus:ring-amber-500 focus:border-transparent transition-all text-slate-800 placeholder-slate-400 pr-12`}
                  />
                  <button
                    type="button"
                    onClick={() => setShowPassword(!showPassword)}
                    className="absolute right-4 top-1/2 -translate-y-1/2 text-slate-400 hover:text-slate-600 transition-colors"
                  >
                    {showPassword ? <EyeOff className="w-5 h-5" /> : <Eye className="w-5 h-5" />}
                  </button>
                </div>
                
                {/* Password Requirements */}
                <div className="mt-3 p-3 bg-slate-50 rounded-xl grid grid-cols-2 gap-2">
                  <PasswordCheck valid={passwordChecks.length} label="8+ characters" />
                  <PasswordCheck valid={passwordChecks.lowercase} label="Lowercase (a-z)" />
                  <PasswordCheck valid={passwordChecks.uppercase} label="Uppercase (A-Z)" />
                  <PasswordCheck valid={passwordChecks.special} label="Special char (!@#)" />
                </div>
              </div>

              {/* Terms */}
              <div className="flex items-start gap-3 p-3 bg-amber-50 rounded-xl border border-amber-100">
                <input
                  type="checkbox"
                  id="terms"
                  checked={agreed}
                  onChange={(e) => setAgreed(e.target.checked)}
                  className="mt-1 w-4 h-4 rounded border-slate-300 text-amber-600 focus:ring-amber-500"
                />
                <label htmlFor="terms" className="text-sm text-slate-600">
                  I agree to the <a href="#" className="text-amber-600 font-medium hover:underline">Terms of Service</a> and <a href="#" className="text-amber-600 font-medium hover:underline">Privacy Policy</a>
                </label>
              </div>

              {/* Submit Button */}
              <button
                onClick={handleSubmit}
                disabled={!isFormValid()}
                className={`w-full font-semibold py-3.5 px-6 rounded-xl transition-all flex items-center justify-center gap-2 group ${
                  isFormValid()
                    ? 'bg-gradient-to-r from-amber-500 to-orange-500 hover:from-amber-600 hover:to-orange-600 text-white shadow-lg shadow-orange-300/50 hover:shadow-orange-400/50'
                    : 'bg-slate-200 text-slate-400 cursor-not-allowed'
                }`}
              >
                Create Account
                <ArrowRight className={`w-5 h-5 ${isFormValid() ? 'group-hover:translate-x-1' : ''} transition-transform`} />
              </button>
            </div>

            <p className="text-center text-slate-500 mt-6">
              Already have an account? <a href="#" className="text-amber-600 font-medium hover:underline">Sign in</a>
            </p>
          </div>

          {/* Footer */}
          <p className="text-center text-slate-400 text-sm mt-6">
            © 2025 InventoryPro. All rights reserved.
          </p>
        </div>
      </div>
    </div>
  );
}