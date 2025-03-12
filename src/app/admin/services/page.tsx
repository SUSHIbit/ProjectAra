'use client';

import { useState, useEffect } from 'react';
import { createClient } from '@/lib/supabase/client';

export default function AdminServicesPage() {
  const supabase = createClient();
  
  const [services, setServices] = useState<any[]>([]);
  const [newService, setNewService] = useState({ name: '', default_price: '' });
  const [editingService, setEditingService] = useState<any | null>(null);
  const [loading, setLoading] = useState(true);
  const [error, setError] = useState<string | null>(null);
  
  useEffect(() => {
    fetchServices();
  }, []);
  
  async function fetchServices() {
    try {
      setLoading(true);
      const { data, error } = await supabase
        .from('services')
        .select('*')
        .order('name');
        
      if (error) throw error;
      
      setServices(data || []);
    } catch (error: any) {
      setError(error.message || 'An error occurred while fetching services');
    } finally {
      setLoading(false);
    }
  }
  
  const handleAddService = async (e: React.FormEvent) => {
    e.preventDefault();
    
    if (!newService.name || !newService.default_price) {
      setError('Please fill in all fields');
      return;
    }
    
    try {
      setLoading(true);
      setError(null);
      
      const { error } = await supabase
        .from('services')
        .insert({
          name: newService.name,
          default_price: parseInt(newService.default_price)
        });
        
      if (error) throw error;
      
      setNewService({ name: '', default_price: '' });
      fetchServices();
      
    } catch (error: any) {
      setError(error.message || 'An error occurred while adding the service');
    } finally {
      setLoading(false);
    }
  };
  
  const handleUpdateService = async (e: React.FormEvent) => {
    e.preventDefault();
    
    if (!editingService?.name || !editingService?.default_price) {
      setError('Please fill in all fields');
      return;
    }
    
    try {
      setLoading(true);
      setError(null);
      
      const { error } = await supabase
        .from('services')
        .update({
          name: editingService.name,
          default_price: parseInt(editingService.default_price.toString())
        })
        .eq('id', editingService.id);
        
      if (error) throw error;
      
      setEditingService(null);
      fetchServices();
      
    } catch (error: any) {
      setError(error.message || 'An error occurred while updating the service');
    } finally {
      setLoading(false);
    }
  };
  
  const handleDeleteService = async (id: string) => {
    if (!confirm('Are you sure you want to delete this service?')) {
      return;
    }
    
    try {
      setLoading(true);
      setError(null);
      
      const { error } = await supabase
        .from('services')
        .delete()
        .eq('id', id);
        
      if (error) throw error;
      
      fetchServices();
      
    } catch (error: any) {
      setError(error.message || 'An error occurred while deleting the service');
    } finally {
      setLoading(false);
    }
  };
  
  return (
    <div className="container mx-auto px-4 py-8">
      <h1 className="text-2xl font-bold mb-6">Manage Services</h1>
      
      {error && (
        <div className="mb-4 p-4 bg-red-100 text-red-700 rounded">
          {error}
        </div>
      )}
      
      <div className="grid grid-cols-1 md:grid-cols-2 gap-8">
        <div>
          <h2 className="text-xl font-semibold mb-4">Add New Service</h2>
          
          <form onSubmit={handleAddService} className="bg-white p-6 rounded-lg shadow-md">
            <div className="mb-4">
              <label htmlFor="name" className="block text-sm font-medium text-gray-700 mb-1">
                Service Name
              </label>
              <input
                type="text"
                id="name"
                value={newService.name}
                onChange={(e) => setNewService({ ...newService, name: e.target.value })}
                className="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-indigo-500 focus:border-indigo-500"
                required
              />
            </div>
            
            <div className="mb-4">
              <label htmlFor="price" className="block text-sm font-medium text-gray-700 mb-1">
                Default Price (in cents)
              </label>
              <input
                type="number"
                id="price"
                value={newService.default_price}
                onChange={(e) => setNewService({ ...newService, default_price: e.target.value })}
                className="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-indigo-500 focus:border-indigo-500"
                required
              />
              {newService.default_price && (
                <p className="text-sm text-gray-500 mt-1">
                  ${(parseInt(newService.default_price) / 100).toFixed(2)}
                </p>
              )}
            </div>
            
            <button
              type="submit"
              disabled={loading}
              className="w-full py-2 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"
            >
              {loading ? 'Adding...' : 'Add Service'}
            </button>
          </form>
        </div>
        
        <div>
          <h2 className="text-xl font-semibold mb-4">Current Services</h2>
          
          {loading && !editingService ? (
            <p>Loading services...</p>
          ) : services.length === 0 ? (
            <p>No services found. Add your first service.</p>
          ) : (
            <div className="bg-white rounded-lg shadow-md overflow-hidden">
              <table className="min-w-full divide-y divide-gray-200">
                <thead className="bg-gray-50">
                  <tr>
                    <th className="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                      Service Name
                    </th>
                    <th className="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                      Default Price
                    </th>
                    <th className="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                      Actions
                    </th>
                  </tr>
                </thead>
                <tbody className="bg-white divide-y divide-gray-200">
                  {services.map((service) => (
                    <tr key={service.id}>
                      <td className="px-6 py-4 whitespace-nowrap">
                        {service.name}
                      </td>
                      <td className="px-6 py-4 whitespace-nowrap">
                        ${(service.default_price / 100).toFixed(2)}
                      </td>
                      <td className="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                        <button
                          onClick={() => setEditingService(service)}
                          className="text-indigo-600 hover:text-indigo-900 mr-4"
                        >
                          Edit
                        </button>
                        <button
                          onClick={() => handleDeleteService(service.id)}
                          className="text-red-600 hover:text-red-900"
                        >
                          Delete
                        </button>
                      </td>
                    </tr>
                  ))}
                </tbody>
              </table>
            </div>
          )}
          
          {editingService && (
            <div className="fixed inset-0 bg-gray-500 bg-opacity-75 flex items-center justify-center p-4 z-50">
              <div className="bg-white rounded-lg p-6 max-w-md w-full">
                <h3 className="text-lg font-medium mb-4">Edit Service</h3>
                
                <form onSubmit={handleUpdateService}>
                  <div className="mb-4">
                    <label htmlFor="edit-name" className="block text-sm font-medium text-gray-700 mb-1">
                      Service Name
                    </label>
                    <input
                      type="text"
                      id="edit-name"
                      value={editingService.name}
                      onChange={(e) => setEditingService({ ...editingService, name: e.target.value })}
                      className="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-indigo-500 focus:border-indigo-500"
                      required
                    />
                  </div>
                  
                  <div className="mb-4">
                    <label htmlFor="edit-price" className="block text-sm font-medium text-gray-700 mb-1">
                      Default Price (in cents)
                    </label>
                    <input
                      type="number"
                      id="edit-price"
                      value={editingService.default_price}
                      onChange={(e) => setEditingService({ ...editingService, default_price: e.target.value })}
                      className="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-indigo-500 focus:border-indigo-500"
                      required
                    />
                    {editingService.default_price && (
                      <p className="text-sm text-gray-500 mt-1">
                        ${(parseInt(editingService.default_price.toString()) / 100).toFixed(2)}
                      </p>
                    )}
                  </div>
                  
                  <div className="flex justify-end space-x-4">
                    <button
                      type="button"
                      onClick={() => setEditingService(null)}
                      className="py-2 px-4 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"
                    >
                      Cancel
                    </button>
                    <button
                      type="submit"
                      disabled={loading}
                      className="py-2 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"
                    >
                      {loading ? 'Updating...' : 'Update Service'}
                    </button>
                  </div>
                </form>
              </div>
            </div>
          )}
        </div>
      </div>
    </div>
  );
}