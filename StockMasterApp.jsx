import React, { useState, useEffect } from 'react';
import { 
  LayoutDashboard, 
  Package, 
  ArrowRightLeft, 
  Settings, 
  LogOut, 
  User, 
  Search, 
  Bell, 
  Menu, 
  X,
  Truck,
  ClipboardList,
  AlertTriangle,
  CheckCircle,
  Filter,
  ChevronRight,
  Box,
  RefreshCw,
  FileText
} from 'lucide-react';

// --- Components ---

const Card = ({ children, className = "" }) => (
  <div className={`bg-gray-900 border border-gray-800 rounded-2xl shadow-lg ${className}`}>
    {children}
  </div>
);

const Badge = ({ children, variant = "primary" }) => {
  const colors = {
    primary: "bg-coral-500/20 text-coral-400 border-coral-500/30",
    success: "bg-green-500/20 text-green-400 border-green-500/30",
    warning: "bg-yellow-500/20 text-yellow-400 border-yellow-500/30",
    neutral: "bg-gray-700 text-gray-300 border-gray-600",
  };
  return (
    <span className={`px-2.5 py-1 rounded-full text-xs font-medium border ${colors[variant] || colors.neutral}`}>
      {children}
    </span>
  );
};

const NavItem = ({ icon: Icon, label, active, onClick }) => (
  <button
    onClick={onClick}
    className={`w-full flex items-center gap-3 px-4 py-3 rounded-xl transition-all duration-200 group ${
      active 
        ? 'bg-coral-500 text-white shadow-neon' 
        : 'text-gray-400 hover:bg-gray-800 hover:text-white'
    }`}
  >
    <Icon size={20} className={active ? 'text-white' : 'text-gray-400 group-hover:text-white'} />
    <span className="font-medium">{label}</span>
  </button>
);

const KPICard = ({ title, value, icon: Icon, trend, trendUp }) => (
  <Card className="p-5 flex items-start justify-between hover:border-coral-500/50 transition-colors cursor-pointer group">
    <div>
      <p className="text-gray-400 text-sm font-medium mb-1">{title}</p>
      <h3 className="text-2xl font-bold text-white mb-2">{value}</h3>
      {trend && (
        <div className={`flex items-center text-xs ${trendUp ? 'text-green-400' : 'text-red-400'}`}>
          <span>{trend}</span>
          <span className="ml-1 text-gray-500">vs last month</span>
        </div>
      )}
    </div>
    <div className="p-3 bg-gray-800 rounded-xl group-hover:bg-coral-500/20 group-hover:text-coral-400 transition-colors">
      <Icon size={20} />
    </div>
  </Card>
);

const OperationCard = ({ title, count, actionLabel, details, color = "coral" }) => (
  <div className={`relative overflow-hidden rounded-3xl border border-gray-800 bg-gray-900 p-6 hover:border-${color}-500 transition-all duration-300 group`}>
    <div className={`absolute top-0 right-0 w-32 h-32 bg-${color}-500/5 rounded-full blur-3xl -mr-16 -mt-16 pointer-events-none`}></div>
    
    <div className="flex justify-between items-start mb-6">
      <h3 className={`text-xl font-bold text-white`}>{title}</h3>
      <div className={`p-2 rounded-lg bg-${color}-500/10 text-${color}-400`}>
        <ArrowRightLeft size={20} />
      </div>
    </div>

    <div className="flex flex-col gap-4">
      <button className={`w-full flex items-center justify-between bg-gray-800 hover:bg-gray-700 text-white px-4 py-3 rounded-xl border border-gray-700 transition-all group-hover:border-${color}-500/50`}>
        <span className="font-medium">{count} {actionLabel}</span>
        <ChevronRight size={18} className="text-gray-500" />
      </button>
      
      <div className="space-y-2 mt-2">
        {details.map((detail, idx) => (
          <div key={idx} className="flex justify-between items-center text-sm">
            <span className="text-gray-400">{detail.label}</span>
            <span className={`font-medium ${detail.highlight ? 'text-coral-400' : 'text-white'}`}>
              {detail.value}
            </span>
          </div>
        ))}
      </div>
    </div>
  </div>
);

// --- Main App Component ---

export default function StockMaster() {
  const [activeTab, setActiveTab] = useState('dashboard');
  const [isSidebarOpen, setIsSidebarOpen] = useState(false);
  const [activeFilter, setActiveFilter] = useState('All');

  // Close sidebar on resize if large screen
  useEffect(() => {
    const handleResize = () => {
      if (window.innerWidth >= 1024) setIsSidebarOpen(false);
    };
    window.addEventListener('resize', handleResize);
    return () => window.removeEventListener('resize', handleResize);
  }, []);

  const renderContent = () => {
    switch(activeTab) {
      case 'products':
        return <div className="p-8 text-white">Products View Placeholder</div>;
      case 'operations':
        return <div className="p-8 text-white">Operations View Placeholder</div>;
      case 'settings':
        return <div className="p-8 text-white">Settings View Placeholder</div>;
      default:
        return (
          <div className="space-y-8 animate-fade-in">
            {/* KPI Row */}
            <div className="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
              <KPICard title="Total Products" value="1,248" icon={Package} trend="+12%" trendUp={true} />
              <KPICard title="Low Stock Items" value="24" icon={AlertTriangle} trend="+4" trendUp={false} />
              <KPICard title="Pending Receipts" value="12" icon={ClipboardList} trend="-2" trendUp={true} />
              <KPICard title="Avg. Delivery Time" value="2.4 Days" icon={Truck} trend="-0.5 Days" trendUp={true} />
            </div>

            {/* Main Operations (Sketch Recreation) */}
            <div className="grid grid-cols-1 lg:grid-cols-2 gap-6">
              <OperationCard 
                title="Receipts" 
                count={4} 
                actionLabel="to receive"
                color="coral"
                details={[
                  { label: 'Late', value: '1 Late', highlight: true },
                  { label: 'Total Operations', value: '6 operations' },
                  { label: 'Waiting', value: '1 waiting' }
                ]}
              />
              <OperationCard 
                title="Delivery Orders" 
                count={4} 
                actionLabel="to deliver"
                color="emerald"
                details={[
                  { label: 'Late', value: '1 Late', highlight: true },
                  { label: 'Waiting', value: '2 waiting' },
                  { label: 'Total Operations', value: '6 operations' }
                ]}
              />
            </div>

            {/* Dynamic Filters & List */}
            <div className="bg-gray-900 border border-gray-800 rounded-2xl p-6">
              <div className="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-6">
                <h3 className="text-lg font-bold text-white">Recent Movements</h3>
                
                {/* Filter Pills */}
                <div className="flex flex-wrap gap-2">
                  {['All', 'Receipts', 'Delivery', 'Internal'].map(filter => (
                    <button
                      key={filter}
                      onClick={() => setActiveFilter(filter)}
                      className={`px-4 py-1.5 rounded-full text-sm font-medium transition-all ${
                        activeFilter === filter
                          ? 'bg-coral-500 text-white'
                          : 'bg-gray-800 text-gray-400 hover:bg-gray-700'
                      }`}
                    >
                      {filter}
                    </button>
                  ))}
                </div>
              </div>

              <div className="overflow-x-auto">
                <table className="w-full text-left border-collapse">
                  <thead>
                    <tr className="text-gray-500 text-sm border-b border-gray-800">
                      <th className="pb-3 font-medium pl-2">Reference</th>
                      <th className="pb-3 font-medium">Product</th>
                      <th className="pb-3 font-medium">Location</th>
                      <th className="pb-3 font-medium">Status</th>
                      <th className="pb-3 font-medium text-right pr-2">Quantity</th>
                    </tr>
                  </thead>
                  <tbody className="text-sm">
                    {[
                      { ref: 'WH/IN/001', prod: 'MacBook Pro M2', loc: 'Partner → Stock', status: 'Ready', qty: 50, type: 'Receipts' },
                      { ref: 'WH/OUT/042', prod: 'Ergo Chair V2', loc: 'Stock → Customer', status: 'Waiting', qty: 12, type: 'Delivery' },
                      { ref: 'WH/INT/009', prod: 'Monitor Stand', loc: 'Shelf A → Shelf B', status: 'Done', qty: 100, type: 'Internal' },
                      { ref: 'WH/OUT/043', prod: 'USB-C Hub', loc: 'Stock → Customer', status: 'Draft', qty: 5, type: 'Delivery' },
                    ].filter(item => activeFilter === 'All' || item.type === activeFilter).map((item, i) => (
                      <tr key={i} className="group border-b border-gray-800/50 hover:bg-gray-800/30 transition-colors">
                        <td className="py-4 pl-2 text-white font-medium">{item.ref}</td>
                        <td className="py-4 text-gray-300">{item.prod}</td>
                        <td className="py-4 text-gray-400 text-xs">{item.loc}</td>
                        <td className="py-4">
                          <Badge variant={
                            item.status === 'Done' ? 'success' : 
                            item.status === 'Ready' ? 'primary' : 
                            item.status === 'Waiting' ? 'warning' : 'neutral'
                          }>
                            {item.status}
                          </Badge>
                        </td>
                        <td className="py-4 text-right pr-2 text-white font-medium">{item.qty}</td>
                      </tr>
                    ))}
                  </tbody>
                </table>
              </div>
            </div>
          </div>
        );
    }
  };

  return (
    <div className="min-h-screen bg-black text-gray-200 font-sans selection:bg-coral-500/30 selection:text-coral-200">
      
      {/* Mobile Sidebar Overlay */}
      {isSidebarOpen && (
        <div 
          className="fixed inset-0 bg-black/80 z-40 lg:hidden backdrop-blur-sm"
          onClick={() => setIsSidebarOpen(false)}
        />
      )}

      {/* Sidebar */}
      <aside className={`fixed top-0 left-0 h-full w-72 bg-gray-950 border-r border-gray-800 z-50 transform transition-transform duration-300 ease-in-out ${isSidebarOpen ? 'translate-x-0' : '-translate-x-full lg:translate-x-0'}`}>
        <div className="h-full flex flex-col">
          {/* Logo Area */}
          <div className="p-6 border-b border-gray-800 flex items-center gap-3">
            <div className="w-10 h-10 bg-coral-500 rounded-xl flex items-center justify-center shadow-neon">
              <Box className="text-white" />
            </div>
            <h1 className="text-2xl font-bold text-white tracking-tight">Stock<span className="text-coral-500">Master</span></h1>
          </div>

          {/* Navigation */}
          <nav className="flex-1 px-4 py-6 space-y-2 overflow-y-auto">
            <div className="mb-6">
              <p className="px-4 text-xs font-semibold text-gray-500 uppercase tracking-wider mb-2">Main</p>
              <NavItem icon={LayoutDashboard} label="Dashboard" active={activeTab === 'dashboard'} onClick={() => setActiveTab('dashboard')} />
              <NavItem icon={Package} label="Products" active={activeTab === 'products'} onClick={() => setActiveTab('products')} />
            </div>
            
            <div className="mb-6">
              <p className="px-4 text-xs font-semibold text-gray-500 uppercase tracking-wider mb-2">Operations</p>
              <NavItem icon={ClipboardList} label="Receipts" onClick={() => {}} />
              <NavItem icon={Truck} label="Delivery Orders" onClick={() => {}} />
              <NavItem icon={RefreshCw} label="Inventory Adj." onClick={() => {}} />
              <NavItem icon={FileText} label="Move History" onClick={() => {}} />
            </div>

            <div>
              <p className="px-4 text-xs font-semibold text-gray-500 uppercase tracking-wider mb-2">System</p>
              <NavItem icon={Settings} label="Settings" active={activeTab === 'settings'} onClick={() => setActiveTab('settings')} />
            </div>
          </nav>

          {/* User Profile */}
          <div className="p-4 border-t border-gray-800">
            <button className="w-full flex items-center gap-3 p-2 rounded-xl hover:bg-gray-900 transition-colors">
              <div className="w-10 h-10 rounded-full bg-gray-800 flex items-center justify-center border border-gray-700 text-coral-500 font-bold">
                A
              </div>
              <div className="flex-1 text-left">
                <p className="text-sm font-medium text-white">Admin User</p>
                <p className="text-xs text-gray-500">Warehouse Manager</p>
              </div>
              <LogOut size={18} className="text-gray-500" />
            </button>
          </div>
        </div>
      </aside>

      {/* Main Content */}
      <main className="lg:ml-72 min-h-screen transition-all duration-300">
        
        {/* Header */}
        <header className="sticky top-0 z-30 bg-black/80 backdrop-blur-md border-b border-gray-800 px-6 py-4">
          <div className="flex items-center justify-between gap-4">
            <div className="flex items-center gap-4">
              <button 
                onClick={() => setIsSidebarOpen(true)}
                className="lg:hidden p-2 text-gray-400 hover:bg-gray-800 rounded-lg"
              >
                <Menu size={24} />
              </button>
              <h2 className="text-xl font-semibold text-white hidden sm:block">
                {activeTab.charAt(0).toUpperCase() + activeTab.slice(1)}
              </h2>
            </div>

            <div className="flex items-center gap-4">
              {/* Search Bar */}
              <div className="hidden md:flex items-center bg-gray-900 border border-gray-800 rounded-xl px-3 py-2 w-64 focus-within:border-coral-500/50 transition-colors">
                <Search size={18} className="text-gray-500" />
                <input 
                  type="text" 
                  placeholder="Search products, refs..." 
                  className="bg-transparent border-none focus:outline-none text-sm text-white ml-2 w-full placeholder-gray-600"
                />
              </div>

              {/* Notification */}
              <button className="relative p-2 text-gray-400 hover:bg-gray-800 hover:text-white rounded-lg transition-colors">
                <Bell size={20} />
                <span className="absolute top-2 right-2 w-2 h-2 bg-coral-500 rounded-full border border-black"></span>
              </button>
            </div>
          </div>
        </header>

        {/* Page Content */}
        <div className="p-6">
          {renderContent()}
        </div>
      </main>

      {/* Tailwind Custom Config Injection (Simulated via Style) */}
      <style>{`
        .shadow-neon {
          box-shadow: 0 0 15px -3px rgba(255, 127, 80, 0.4);
        }
        /* Adding custom colors without full config */
        .bg-coral-500 { background-color: #ff7f50; }
        .text-coral-500 { color: #ff7f50; }
        .text-coral-400 { color: #ff8f6b; }
        .border-coral-500 { border-color: #ff7f50; }
        .bg-coral-500\\/20 { background-color: rgba(255, 127, 80, 0.2); }
        .bg-coral-500\\/10 { background-color: rgba(255, 127, 80, 0.1); }
        .bg-coral-500\\/5 { background-color: rgba(255, 127, 80, 0.05); }
        .hover\\:border-coral-500\\/50:hover { border-color: rgba(255, 127, 80, 0.5); }
        .hover\\:text-coral-400:hover { color: #ff8f6b; }
      `}</style>
    </div>
  );
}