# GST FEATURE - QUICK TESTING GUIDE

## 🚀 QUICK START

### 1. Database Setup
```bash
php artisan migrate
```

### 2. Test New Business Registration
1. Go to `/register`
2. Fill business details
3. **NEW:** Select State from dropdown
4. **NEW:** Select City from dependent dropdown
5. Complete registration
6. Verify in database: `businesses` table has `business_state` and `business_city`

---

## 🧪 TEST SCENARIOS

### Scenario A: Auto-Fill GST Rate & Description
**Steps:**
1. Login to existing business
2. Go to Quotations → Create New
3. Add item row
4. Select a commodity from dropdown
5. **VERIFY:**
   - ✅ Description auto-fills
   - ✅ GST rate auto-fills (from commodity)
   - ✅ Unit price auto-fills
   - ✅ Total recalculates

---

### Scenario B: Intra-State GST (CGST + SGST)
**Setup:**
- Business State: Maharashtra
- Customer State: Maharashtra
- Commodity GST: 18%

**Steps:**
1. Create quotation with above setup
2. Add item with 18% GST
3. Save quotation
4. Download PDF

**VERIFY PDF Shows:**
```
Subtotal: ₹10,000.00
CGST: ₹900.00    (9%)
SGST: ₹900.00    (9%)
Grand Total: ₹11,800.00
```

---

### Scenario C: Inter-State GST (IGST)
**Setup:**
- Business State: Maharashtra
- Customer State: Gujarat
- Commodity GST: 18%

**Steps:**
1. Create quotation with above setup
2. Add item with 18% GST
3. Save quotation
4. Download PDF

**VERIFY PDF Shows:**
```
Subtotal: ₹10,000.00
IGST: ₹1,800.00   (18%)
Grand Total: ₹11,800.00
```

---

### Scenario D: State Validation Block
**Setup:**
- Business WITHOUT `business_state` (old business)

**Steps:**
1. Try to create quotation
2. **VERIFY:** Redirected to business profile
3. **VERIFY:** Error message: "Please complete your business state details to apply GST correctly."
4. Update business state in profile
5. Retry quotation creation
6. **VERIFY:** Allowed to proceed

---

## 📊 DATABASE CHECKS

### Check Business State
```sql
SELECT id, name, business_state, business_city FROM businesses;
```

### Check Commodity GST Rates
```sql
SELECT id, name, gst_rate, description FROM materials LIMIT 10;
```

### Check Customer States
```sql
SELECT id, name, state, city FROM customers LIMIT 10;
```

---

## 🔍 DEBUGGING

### If GST not auto-filling:
1. Check browser console for JS errors
2. Verify materials JSON includes `gst_rate` and `description`
3. Check commodity has `gst_rate` in database

### If PDF shows "Tax" instead of CGST/SGST:
1. Check business has `business_state` set
2. Check customer has `state` set
3. Verify `GstCalculationService` is being called
4. Check `$gst_breakdown` variable in PDF view

### If state dropdown empty:
1. Run: `php artisan db:seed --class=StateCitySeeder`
2. Check `states` table has data
3. Verify API route `/api/cities/{state}` works

---

## 🎯 EXPECTED BEHAVIOR

### GST Calculation Logic
```
IF business_state == customer_state:
    CGST = GST / 2
    SGST = GST / 2
ELSE:
    IGST = Full GST
```

### Examples:
| GST Rate | Same State | Different State |
|----------|------------|-----------------|
| 5%       | CGST 2.5% + SGST 2.5% | IGST 5% |
| 12%      | CGST 6% + SGST 6% | IGST 12% |
| 18%      | CGST 9% + SGST 9% | IGST 18% |
| 28%      | CGST 14% + SGST 14% | IGST 28% |

---

## ⚠️ COMMON ISSUES

### Issue: City dropdown not populating
**Solution:** Check API route is public (not behind auth)

### Issue: Old businesses can't create quotations
**Solution:** They need to update `business_state` in profile

### Issue: PDF shows wrong GST breakdown
**Solution:** Verify customer has `state` field populated

### Issue: GST rate not auto-filling
**Solution:** Check commodity has `gst_rate` in database

---

## 📝 MANUAL TEST CHECKLIST

- [ ] New business registration with state/city
- [ ] Commodity selection auto-fills GST
- [ ] Commodity selection auto-fills description
- [ ] Intra-state quotation shows CGST+SGST
- [ ] Inter-state quotation shows IGST
- [ ] Intra-state invoice shows CGST+SGST
- [ ] Inter-state invoice shows IGST
- [ ] State validation blocks quotation creation
- [ ] State validation blocks invoice creation
- [ ] Totals calculate correctly
- [ ] PDF formatting not broken
- [ ] Existing quotations still work

---

## 🔧 ROLLBACK (If Needed)

```bash
# Rollback migration
php artisan migrate:rollback --step=1

# Clear caches
php artisan config:clear
php artisan cache:clear
php artisan view:clear
```

---

**Testing Date:** _____________
**Tested By:** _____________
**Status:** [ ] PASS [ ] FAIL
**Notes:** _____________________________________________
